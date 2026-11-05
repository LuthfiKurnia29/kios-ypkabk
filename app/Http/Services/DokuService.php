<?php

namespace App\Http\Services;

use App\Models\Purchase;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class DokuService extends BaseService
{
    public function __construct(
        protected PurchaseService $purchaseService,
    ) {}

    /**
     * Get the active DOKU environment.
     */
    public function getEnvironment(): string
    {
        return config('doku.environment', 'dummy');
    }

    /**
     * Check if currently running in dummy / simulator mode.
     */
    public function isDummy(): bool
    {
        return $this->getEnvironment() === 'dummy';
    }

    /**
     * Generate checkout payment URL for the given purchase order.
     * In dummy mode: returns route to local interactive simulator.
     * In live/sandbox mode: calls DOKU Checkout API.
     */
    public function generatePaymentUrl(Purchase $purchase): string
    {
        if ($this->isDummy()) {
            return route('payment.doku.simulator', ['purchase' => $purchase->order_code]);
        }

        return $this->requestDokuCheckout($purchase);
    }

    /**
     * Send payment request to DOKU Checkout API (Sandbox or Production).
     */
    protected function requestDokuCheckout(Purchase $purchase): string
    {
        $env = $this->getEnvironment();
        $baseUrl = config("doku.api_url.{$env}", config('doku.api_url.sandbox'));
        $targetPath = '/checkout/v1/payment';
        $endpoint = rtrim($baseUrl, '/').$targetPath;

        $clientId = config('doku.client_id');
        $secretKey = config('doku.secret_key');
        $requestId = (string) Str::uuid();
        $timestamp = gmdate('Y-m-d\TH:i:s\Z');

        $returnUrl = url(config('doku.return_path')).'?order='.urlencode($purchase->order_code);

        $payload = [
            'order' => [
                'invoice_number' => $purchase->order_code,
                'amount' => (int) round((float) $purchase->total_price),
                'callback_url' => $returnUrl,
                'auto_redirect' => true,
            ],
            'payment' => [
                'payment_due_date' => 60, // 60 minutes expiry
            ],
            'customer' => [
                'name' => $purchase->customer_name ?: 'Donatur Kios Mandiri',
                'phone' => $purchase->customer_phone ?: '08123456789',
                'address' => $purchase->customer_address ?: 'Indonesia',
            ],
        ];

        $rawBody = json_encode($payload);
        $signature = $this->generateSignature($clientId, $requestId, $timestamp, $targetPath, $rawBody, $secretKey);

        try {
            $response = Http::withHeaders([
                'Client-Id' => $clientId,
                'Request-Id' => $requestId,
                'Request-Timestamp' => $timestamp,
                'Signature' => $signature,
                'Content-Type' => 'application/json',
            ])->timeout(10)->post($endpoint, $payload);

            if ($response->successful()) {
                $data = $response->json();
                $paymentUrl = $data['response']['payment']['url'] ?? null;

                if ($paymentUrl) {
                    return $paymentUrl;
                }
            }

            // Fallback to simulator if API responds with unexpected shape
            return route('payment.doku.simulator', ['purchase' => $purchase->order_code]);
        } catch (Exception $e) {
            // Graceful fallback to simulator on connection failure
            return route('payment.doku.simulator', ['purchase' => $purchase->order_code]);
        }
    }

    /**
     * Generate HMAC-SHA256 signature according to DOKU / Jokul specification.
     */
    public function generateSignature(
        string $clientId,
        string $requestId,
        string $timestamp,
        string $targetPath,
        string $rawBody,
        string $secretKey
    ): string {
        $digest = base64_encode(hash('sha256', $rawBody, true));
        $component = "Client-Id:{$clientId}\n".
            "Request-Id:{$requestId}\n".
            "Request-Timestamp:{$timestamp}\n".
            "Request-Target:{$targetPath}\n".
            "Digest:{$digest}";

        $rawSignature = hash_hmac('sha256', $component, $secretKey, true);

        return 'HMACSHA256='.base64_encode($rawSignature);
    }

    /**
     * Verify incoming webhook notification signature from DOKU.
     */
    public function verifyWebhookSignature(
        string $clientId,
        string $requestId,
        string $timestamp,
        string $targetPath,
        string $rawBody,
        string $signature
    ): bool {
        if ($this->isDummy()) {
            return true;
        }

        $expected = $this->generateSignature(
            $clientId,
            $requestId,
            $timestamp,
            $targetPath,
            $rawBody,
            config('doku.secret_key')
        );

        return hash_equals($expected, $signature);
    }

    /**
     * Process DOKU webhook notification payload and update order state.
     *
     * @param  array<string, mixed>  $payload
     */
    public function handleNotification(array $payload): Purchase
    {
        $orderCode = $payload['order']['invoice_number']
            ?? $payload['invoice_number']
            ?? $payload['order_code']
            ?? null;

        if (! $orderCode) {
            throw new Exception('Invoice number tidak ditemukan pada notifikasi.');
        }

        /** @var Purchase $purchase */
        $purchase = Purchase::with(['detailPurchases.product'])->where('order_code', $orderCode)->firstOrFail();

        $transactionStatus = strtoupper(
            $payload['transaction']['status']
            ?? $payload['status']
            ?? 'SUCCESS'
        );

        $channel = $payload['channel']['id']
            ?? $payload['payment_channel']
            ?? 'DOKU_CHECKOUT';

        $reference = $payload['transaction']['id']
            ?? $payload['payment_reference']
            ?? ('TRX-DOKU-'.strtoupper(Str::random(10)));

        return DB::transaction(function () use ($purchase, $transactionStatus, $channel, $reference) {
            if ($transactionStatus === 'SUCCESS' || $transactionStatus === 'PAID') {
                $purchase->update([
                    'payment_method' => 'doku',
                    'payment_channel' => $channel,
                    'payment_reference' => $reference,
                    'paid_at' => now(),
                ]);

                return $this->purchaseService->updateStatus(
                    $purchase,
                    'paid',
                    "Pembayaran via DOKU ({$channel}) berhasil diverifikasi. Ref: {$reference}"
                );
            }

            if (in_array($transactionStatus, ['FAILED', 'EXPIRED', 'CANCELLED'])) {
                $purchase->update([
                    'payment_method' => 'doku',
                    'payment_channel' => $channel,
                    'payment_reference' => $reference,
                ]);

                return $this->purchaseService->updateStatus(
                    $purchase,
                    'cancelled',
                    "Pembayaran via DOKU ({$channel}) gagal/kadaluarsa ({$transactionStatus}). Stok dikembalikan."
                );
            }

            return $purchase;
        });
    }

    /**
     * Perform interactive simulation action on the dummy simulator.
     */
    public function simulateAction(Purchase $purchase, string $action, ?string $channel = null): Purchase
    {
        $channel = $channel ?: 'QRIS';
        $reference = 'DOKU-SIM-'.strtoupper(Str::random(8));

        return DB::transaction(function () use ($purchase, $action, $channel, $reference) {
            if ($action === 'paid') {
                $purchase->update([
                    'payment_method' => 'doku',
                    'payment_channel' => $channel,
                    'payment_reference' => $reference,
                    'paid_at' => now(),
                ]);

                return $this->purchaseService->updateStatus(
                    $purchase,
                    'paid',
                    "Simulasi pembayaran DOKU ({$channel}) berhasil. Ref: {$reference}"
                );
            }

            if (in_array($action, ['cancelled', 'expired'])) {
                $purchase->update([
                    'payment_method' => 'doku',
                    'payment_channel' => $channel,
                    'payment_reference' => $reference,
                ]);

                return $this->purchaseService->updateStatus(
                    $purchase,
                    'cancelled',
                    "Simulasi {$action} DOKU ({$channel}). Stok produk dikembalikan."
                );
            }

            return $purchase;
        });
    }
}
