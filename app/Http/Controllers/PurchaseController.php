<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminExportRequest;
use App\Http\Services\ExportService;
use App\Http\Services\PurchaseService;
use App\Models\Purchase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class PurchaseController extends Controller
{
    public function __construct(
        protected PurchaseService $purchaseService,
    ) {}

    /**
     * Display a listing of purchases.
     */
    public function index(Request $request): JsonResponse|View
    {
        $purchases = $this->purchaseService->getPurchases(
            $request->all(),
            $request->integer('per_page', 15)
        );

        if ($request->wantsJson()) {
            return response()->json($purchases);
        }

        return view('admin.pages.purchases', compact('purchases'));
    }

    /**
     * Display the specified purchase order.
     */
    public function show(Request $request, Purchase|int|string $purchase): JsonResponse|View
    {
        $order = $this->purchaseService->getPurchase($purchase);

        if ($request->wantsJson()) {
            return response()->json($order);
        }

        return view('user.pages.purchase-show', ['purchase' => $order]);
    }

    /**
     * Store a newly created purchase order.
     */
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $hasSeparateAddress = $request->filled('city') || $request->filled('street_address');

        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:50'],
            'city' => [$hasSeparateAddress ? 'required' : 'nullable', 'string', 'max:100'],
            'district' => [$hasSeparateAddress ? 'required' : 'nullable', 'string', 'max:100'],
            'village' => [$hasSeparateAddress ? 'required' : 'nullable', 'string', 'max:100'],
            'street_address' => [$hasSeparateAddress ? 'required' : 'nullable', 'string', 'max:255'],
            'address' => [$hasSeparateAddress ? 'required' : 'nullable', 'string', 'max:1000'],
            'customer_address' => ['nullable', 'string', 'max:1000'],
            'payment_method' => ['nullable', 'in:qris,bank_transfer'],
            'bank_name' => ['nullable', 'string', 'max:50'],
            'notes' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.price' => ['nullable', 'numeric', 'min:0'],
        ]);

        $address = $validated['address'] ?? $validated['customer_address'] ?? null;

        $customerData = [
            'user_id' => $request->user()?->id,
            'customer_name' => $validated['customer_name'],
            'customer_phone' => $validated['customer_phone'],
            'city' => $validated['city'] ?? null,
            'district' => $validated['district'] ?? null,
            'village' => $validated['village'] ?? null,
            'street_address' => $validated['street_address'] ?? null,
            'address' => $address,
            'customer_address' => $address,
            'payment_method' => $validated['payment_method'] ?? 'qris',
            'bank_name' => $validated['bank_name'] ?? null,
            'notes' => $validated['notes'] ?? null,
        ];

        $purchase = $this->purchaseService->createPurchase($customerData, $validated['items']);

        if ($request->wantsJson()) {
            $qrisPayload = $this->purchaseService->generateDynamicQris($purchase->total_price);

            return response()->json([
                'message' => 'Pesanan berhasil dibuat.',
                'data' => $purchase,
                'order_url' => route('purchases.show', $purchase->order_code),
                'whatsapp_receipt_url' => $purchase->whatsapp_receipt_url,
                'payment_method' => $purchase->payment_method,
                'qris' => [
                    'payload' => $qrisPayload,
                    'total_price' => (float) $purchase->total_price,
                    'formatted_price' => 'Rp '.number_format($purchase->total_price, 0, ',', '.'),
                    'upload_proof_url' => route('purchases.upload-proof', $purchase->order_code),
                ],
                'bank' => [
                    'bank_name' => 'BCA (Bank Central Asia)',
                    'account_number' => '018-384-9900',
                    'raw_account_number' => '0183849900',
                    'account_holder' => 'Yayasan Peduli Kasih ABK',
                ],
            ], 201);
        }

        return redirect()->route('purchases.show', $purchase->order_code)
            ->with('success', "Pesanan {$purchase->order_code} berhasil dibuat.");
    }

    /**
     * Upload payment proof for the specified purchase order.
     */
    public function uploadPaymentProof(Request $request, Purchase|int|string $purchase): JsonResponse|RedirectResponse
    {
        $request->validate([
            'payment_proof' => ['required', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $order = $this->purchaseService->getPurchase($purchase);

        $file = $request->file('payment_proof');
        $filename = 'proof-'.$order->order_code.'-'.time().'.'.$file->getClientOriginalExtension();
        $path = $file->storeAs('payment_proofs', $filename, 'public');

        $updatedOrder = $this->purchaseService->savePaymentProof($order, $path);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Bukti pembayaran berhasil diunggah.',
                'data' => [
                    'order_code' => $updatedOrder->order_code,
                    'payment_proof' => asset('storage/'.$path),
                ],
            ]);
        }

        return redirect()->back()->with('success', 'Bukti pembayaran berhasil diunggah.');
    }

    /**
     * Update the status of the specified purchase order.
     */
    public function updateStatus(Request $request, Purchase|int|string $purchase): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,paid,shipped,completed,cancelled'],
            'notes' => ['nullable', 'string'],
        ]);

        $order = $this->purchaseService->updateStatus($purchase, $validated['status'], $validated['notes'] ?? null);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Status pesanan berhasil diperbarui.',
                'data' => $order,
            ]);
        }

        return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui.');
    }

    /**
     * Update courier shipping info and tracking number.
     */
    public function updateShipping(Request $request, Purchase|int|string $purchase): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'courier_name' => ['required', 'string', 'in:JNE,J&T Express,SiCepat,AnterAja,Pos Indonesia,Kurir Internal,Ambil di Kios'],
            'tracking_number' => ['required_unless:courier_name,Ambil di Kios', 'nullable', 'string', 'max:100'],
            'shipping_receipt' => ['nullable', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ], [
            'courier_name.required' => 'Ekspedisi / kurir wajib dipilih.',
            'tracking_number.required_unless' => 'Nomor resi wajib diisi jika bukan Ambil di Kios.',
            'shipping_receipt.max' => 'Foto bukti resi maksimal 5MB.',
        ]);

        $order = $this->purchaseService->getPurchase($purchase);

        $data = [
            'courier_name' => $validated['courier_name'],
            'tracking_number' => $validated['tracking_number'] ?? ($validated['courier_name'] === 'Ambil di Kios' ? '-' : null),
        ];

        if ($request->hasFile('shipping_receipt')) {
            $file = $request->file('shipping_receipt');
            $filename = 'receipt-'.$order->order_code.'-'.time().'.'.$file->getClientOriginalExtension();
            $path = $file->storeAs('shipping_receipts', $filename, 'public');
            $data['shipping_receipt_path'] = $path;
        }

        // If previously paid, update status to shipped
        if ($order->status === 'paid') {
            $data['status'] = 'shipped';
        }

        $order->update($data);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Informasi pengiriman berhasil diperbarui.',
                'data' => $order->fresh(),
            ]);
        }

        return redirect()->back()->with('success', "Informasi pengiriman pesanan {$order->order_code} berhasil disimpan.");
    }

    /**
     * Export purchase orders to Excel or PDF.
     */
    public function export(AdminExportRequest $request, ExportService $exportService): SymfonyResponse
    {
        $validated = $request->validated();
        $range = $exportService->resolveDateRange($validated['period_type'], $validated['period']);
        $data = $exportService->getPurchasesData($range['start'], $range['end']);

        return $exportService->export('purchases', $validated['format'], $range, $data);
    }
}
