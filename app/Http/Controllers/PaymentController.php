<?php

namespace App\Http\Controllers;

use App\Http\Services\DokuService;
use App\Http\Services\PurchaseService;
use App\Models\Purchase;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function __construct(
        protected DokuService $dokuService,
        protected PurchaseService $purchaseService,
    ) {}

    /**
     * Initiate payment for the given purchase.
     */
    public function checkout(Request $request, Purchase|string $purchase): JsonResponse|RedirectResponse
    {
        $order = $this->purchaseService->getPurchase($purchase);
        $paymentUrl = $this->dokuService->generatePaymentUrl($order);

        if ($request->wantsJson()) {
            return response()->json([
                'order_code' => $order->order_code,
                'payment_url' => $paymentUrl,
            ]);
        }

        return redirect()->away($paymentUrl);
    }

    /**
     * Display the interactive DOKU dummy simulator page.
     */
    public function simulator(Purchase|string $purchase): View|RedirectResponse
    {
        $order = $this->purchaseService->getPurchase($purchase);

        // Pre-generate realistic dummy payment details
        $dummyDetails = [
            'qris_string' => '00020101021226680016ID.DOKU.WWW011893600918'.$order->order_code.'5204581253033605802ID5918KIOS MANDIRI YPKABK6008SIDOARJO6304A1B2',
            'va_bca' => '3901'.str_pad((string) $order->id, 8, '0', STR_PAD_LEFT),
            'va_mandiri' => '88899'.str_pad((string) $order->id, 8, '0', STR_PAD_LEFT),
            'va_bri' => '12800'.str_pad((string) $order->id, 8, '0', STR_PAD_LEFT),
            'va_bni' => '98800'.str_pad((string) $order->id, 8, '0', STR_PAD_LEFT),
            'retail_code' => 'DOKU'.strtoupper(substr(md5($order->order_code), 0, 8)),
        ];

        return view('user.pages.payment.doku-simulator', [
            'purchase' => $order,
            'dummy' => $dummyDetails,
        ]);
    }

    /**
     * Handle simulation trigger action from the dummy simulator.
     */
    public function simulateAction(Request $request, Purchase|string $purchase): JsonResponse|RedirectResponse
    {
        $order = $this->purchaseService->getPurchase($purchase);

        $validated = $request->validate([
            'action' => ['required', 'in:paid,cancelled,expired'],
            'channel' => ['nullable', 'string', 'max:50'],
        ]);

        $updatedOrder = $this->dokuService->simulateAction(
            $order,
            $validated['action'],
            $validated['channel'] ?? 'QRIS'
        );

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Simulasi berhasil dijalankan.',
                'order' => $updatedOrder,
                'redirect_url' => $validated['action'] === 'paid'
                    ? route('purchases.success', ['purchase' => $order->order_code])
                    : route('keranjang'),
            ]);
        }

        if ($validated['action'] === 'paid') {
            return redirect()->route('purchases.success', ['purchase' => $order->order_code])
                ->with('success', 'Pembayaran simulasi DOKU berhasil.');
        }

        return redirect()->route('keranjang')
            ->with('error', 'Pesanan dibatalkan / gagal diselesaikan.');
    }

    /**
     * DOKU Webhook Notification Endpoint.
     */
    public function notify(Request $request): JsonResponse
    {
        $clientId = (string) $request->header('Client-Id', '');
        $requestId = (string) $request->header('Request-Id', '');
        $timestamp = (string) $request->header('Request-Timestamp', '');
        $signature = (string) $request->header('Signature', '');
        $targetPath = $request->getRequestUri();
        $rawBody = (string) $request->getContent();

        $isValid = $this->dokuService->verifyWebhookSignature(
            $clientId,
            $requestId,
            $timestamp,
            $targetPath,
            $rawBody,
            $signature
        );

        if (! $isValid) {
            return response()->json(['message' => 'Invalid signature.'], 401);
        }

        try {
            $purchase = $this->dokuService->handleNotification($request->all());

            return response()->json([
                'status' => 'OK',
                'order_code' => $purchase->order_code,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'ERROR',
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Return URL after DOKU checkout completes.
     */
    public function returnUrl(Request $request): RedirectResponse
    {
        $orderCode = $request->query('order')
            ?? $request->query('invoice_number')
            ?? $request->query('order_code');

        if ($orderCode) {
            return redirect()->route('purchases.success', ['purchase' => $orderCode]);
        }

        return redirect()->route('home');
    }

    /**
     * Display the payment success and donation receipt page.
     */
    public function success(Purchase|string $purchase): View
    {
        $order = $this->purchaseService->getPurchase($purchase);

        return view('user.pages.payment.success', [
            'purchase' => $order,
        ]);
    }
}
