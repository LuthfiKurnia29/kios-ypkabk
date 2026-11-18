<?php

namespace App\Http\Services;

use App\Models\DetailPurchase;
use App\Models\LogPurchase;
use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use InvalidArgumentException;

class PurchaseService extends BaseService
{
    public function __construct(
        protected ProductService $productService,
    ) {}

    /**
     * Get purchases with optional filters and pagination.
     *
     * @param  array{
     *     status?: ?string,
     *     user_id?: ?int,
     *     search?: ?string,
     * }  $filters
     * @return LengthAwarePaginator<Purchase>|Collection<int, Purchase>
     */
    public function getPurchases(array $filters = [], ?int $perPage = 15): LengthAwarePaginator|Collection
    {
        $query = Purchase::with(['user', 'detailPurchases.product']);

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (! empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (! empty($filters['search'])) {
            $search = trim($filters['search']);
            $query->where(function (Builder $q) use ($search) {
                $q->where('order_code', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        $query->latest();

        return $perPage !== null ? $query->paginate($perPage) : $query->get();
    }

    /**
     * Retrieve a single purchase by ID, order code, or instance.
     */
    public function getPurchase(Purchase|int|string $purchase): Purchase
    {
        if ($purchase instanceof Purchase) {
            return $purchase->loadMissing(['user', 'detailPurchases.product', 'logPurchases']);
        }

        if (is_numeric($purchase)) {
            return Purchase::with(['user', 'detailPurchases.product', 'logPurchases'])->findOrFail((int) $purchase);
        }

        return Purchase::with(['user', 'detailPurchases.product', 'logPurchases'])
            ->where('order_code', $purchase)
            ->firstOrFail();
    }

    /**
     * Create a purchase order with detail items and automatic stock deduction.
     *
     * @param  array{
     *     user_id?: ?int,
     *     customer_name: string,
     *     customer_phone: string,
     *     customer_address?: ?string,
     *     notes?: ?string,
     * }  $customerData
     * @param  array<int, array{product_id: int, quantity: int, price?: ?float}>  $items
     */
    public function createPurchase(array $customerData, array $items): Purchase
    {
        if (empty($items)) {
            throw new InvalidArgumentException('Pesanan harus memiliki minimal satu produk.');
        }

        return DB::transaction(function () use ($customerData, $items) {
            $orderCode = 'ORD-'.now()->format('Ymd').'-'.strtoupper(Str::random(5));

            $totalPrice = 0;
            $preparedDetails = [];

            foreach ($items as $item) {
                /** @var Product $product */
                $product = $this->productService->getDataProduct($item['product_id']);
                $quantity = max(1, (int) $item['quantity']);

                if ($product->stock < $quantity) {
                    throw new InvalidArgumentException("Stok produk {$product->name} tidak mencukupi (sisa: {$product->stock}).");
                }

                $unitPrice = isset($item['price']) ? (float) $item['price'] : (float) $product->price;
                $lineTotal = $unitPrice * $quantity;
                $totalPrice += $lineTotal;

                $preparedDetails[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'price' => $unitPrice,
                    'total_price' => $lineTotal,
                ];
            }

            $purchase = Purchase::create([
                'user_id' => $customerData['user_id'] ?? null,
                'order_code' => $orderCode,
                'customer_name' => trim($customerData['customer_name']),
                'customer_phone' => trim($customerData['customer_phone']),
                'customer_address' => $customerData['customer_address'] ?? null,
                'total_price' => $totalPrice,
                'status' => 'pending',
                'notes' => $customerData['notes'] ?? null,
            ]);

            foreach ($preparedDetails as $detail) {
                DetailPurchase::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $detail['product']->id,
                    'quantity' => $detail['quantity'],
                    'price' => $detail['price'],
                    'total_price' => $detail['total_price'],
                ]);

                // Deduct product stock
                $this->productService->updateStock($detail['product'], -$detail['quantity']);
            }

            LogPurchase::create([
                'purchase_id' => $purchase->id,
                'action' => 'created',
                'desc' => 'Pesanan baru dibuat.',
            ]);

            return $purchase->load(['user', 'detailPurchases.product', 'logPurchases']);
        });
    }

    /**
     * Update the status of a purchase and write to purchase audit log.
     */
    public function updateStatus(Purchase|int|string $purchase, string $newStatus, ?string $notes = null): Purchase
    {
        return DB::transaction(function () use ($purchase, $newStatus, $notes) {
            $order = $this->getPurchase($purchase);
            $previousStatus = $order->status;

            if ($previousStatus === $newStatus) {
                return $order;
            }

            // If order was cancelled, restore stocks
            if ($newStatus === 'cancelled' && $previousStatus !== 'cancelled') {
                foreach ($order->detailPurchases as $item) {
                    $this->productService->updateStock($item->product_id, (int) $item->quantity);
                }
            }

            // If reactivating a previously cancelled order, re-deduct stocks
            if ($previousStatus === 'cancelled' && $newStatus !== 'cancelled') {
                foreach ($order->detailPurchases as $item) {
                    $this->productService->updateStock($item->product_id, -((int) $item->quantity));
                }
            }

            $order->update([
                'status' => $newStatus,
            ]);

            LogPurchase::create([
                'purchase_id' => $order->id,
                'action' => "status_changed_to_{$newStatus}",
                'desc' => $notes ?? "Status pesanan diubah dari {$previousStatus} ke {$newStatus}.",
            ]);

            SiteStatsService::clearCache();

            return $order->fresh(['user', 'detailPurchases.product', 'logPurchases']);
        });
    }

    /**
     * Delete a purchase record and its associated logs/details.
     */
    public function deletePurchase(Purchase|int|string $purchase): bool
    {
        $order = $this->getPurchase($purchase);

        return (bool) $order->delete();
    }

    /**
     * Save uploaded payment proof for a purchase order.
     */
    public function savePaymentProof(Purchase|int|string $purchase, string $path): Purchase
    {
        $order = $this->getPurchase($purchase);

        $order->update([
            'payment_proof' => $path,
            'payment_method' => 'qris',
        ]);

        LogPurchase::create([
            'purchase_id' => $order->id,
            'action' => 'payment_proof_uploaded',
            'desc' => 'Bukti pembayaran diunggah oleh pembeli.',
        ]);

        return $order->fresh(['user', 'detailPurchases.product', 'logPurchases']);
    }

    /**
     * Generate dynamic QRIS EMVCo string payload from base static QRIS.
     */
    public function generateDynamicQris(float|int $amount, ?string $baseQris = null): string
    {
        $baseQris = $baseQris ?: config('services.qris.base_string');

        // Default standard EMVCo static QRIS for KIOS MANDIRI YPKABK
        if (empty($baseQris)) {
            $baseQris = '00020101021126600016ID.CO.QRIS.WWW0118936009990000000000021000000000000303UMI51440014ID.CO.QRIS.WWW02150000000000000000303UMI5204581453033605802ID5920KIOS MANDIRI YPKABK6008SIDOARJO61056121862070703A016304';
        }

        // 1. Strip existing CRC tag (Tag 63) if present
        $payload = preg_replace('/6304[A-Fa-f0-9]{4}$/', '', trim($baseQris));
        if (str_ends_with($payload, '6304')) {
            $payload = substr($payload, 0, -4);
        }

        // 2. Set Point of Initiation to Dynamic (Tag 01: '12')
        if (str_contains($payload, '010211')) {
            $payload = str_replace('010211', '010212', $payload);
        } elseif (! str_contains($payload, '010212')) {
            if (str_starts_with($payload, '000201')) {
                $payload = '000201010212'.substr($payload, 6);
            }
        }

        // 3. Remove existing tag 54 if present (format: 54LLVALUE)
        $payload = preg_replace('/54\d{2}[0-9.]+(\d{2})?/', '', $payload);

        // 4. Format amount tag (Tag 54)
        $amountInt = max(0, (int) round($amount));
        $amountStr = (string) $amountInt;
        $amountLen = str_pad((string) strlen($amountStr), 2, '0', STR_PAD_LEFT);
        $tag54 = '54'.$amountLen.$amountStr;

        // Insert tag 54 before tag 58 (Country code)
        if (str_contains($payload, '5802ID')) {
            $payload = str_replace('5802ID', $tag54.'5802ID', $payload);
        } else {
            $payload .= $tag54;
        }

        // 5. Append Tag 6304 (CRC placeholder)
        $payloadToHash = $payload.'6304';

        // 6. Compute CRC16-CCITT (False: poly 0x1021, init 0xFFFF)
        $crc = $this->calculateCrc16Ccitt($payloadToHash);

        return $payloadToHash.strtoupper(str_pad(dechex($crc), 4, '0', STR_PAD_LEFT));
    }

    /**
     * Calculate CRC16-CCITT (False / XMODEM variant: poly 0x1021, init 0xFFFF).
     */
    protected function calculateCrc16Ccitt(string $str): int
    {
        $crc = 0xFFFF;
        $len = strlen($str);
        for ($i = 0; $i < $len; $i++) {
            $crc ^= (ord($str[$i]) << 8);
            for ($j = 0; $j < 8; $j++) {
                if ($crc & 0x8000) {
                    $crc = (($crc << 1) ^ 0x1021) & 0xFFFF;
                } else {
                    $crc = ($crc << 1) & 0xFFFF;
                }
            }
        }

        return $crc;
    }
}
