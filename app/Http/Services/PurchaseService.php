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

        if (! empty($filters['order_type'])) {
            $query->where('order_type', $filters['order_type']);
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
     *     address?: ?string,
     *     city?: ?string,
     *     district?: ?string,
     *     village?: ?string,
     *     street_address?: ?string,
     *     payment_method?: ?string,
     *     bank_name?: ?string,
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

            $address = $customerData['address'] ?? $customerData['customer_address'] ?? null;

            $purchase = Purchase::create([
                'user_id' => $customerData['user_id'] ?? null,
                'order_code' => $orderCode,
                'customer_name' => trim($customerData['customer_name']),
                'customer_phone' => trim($customerData['customer_phone']),
                'customer_address' => $address,
                'address' => $address,
                'city' => $customerData['city'] ?? null,
                'district' => $customerData['district'] ?? null,
                'village' => $customerData['village'] ?? null,
                'street_address' => $customerData['street_address'] ?? null,
                'payment_method' => $customerData['payment_method'] ?? 'qris',
                'bank_name' => ($customerData['payment_method'] ?? 'qris') === 'bank_transfer'
                    ? ($customerData['bank_name'] ?? 'BCA')
                    : null,
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
     * Create a print-on-demand purchase order awaiting admin approval.
     *
     * @param  array{
     *     user_id?: ?int,
     *     customer_name: string,
     *     customer_phone: string,
     *     customer_address?: ?string,
     *     address?: ?string,
     *     city?: ?string,
     *     district?: ?string,
     *     village?: ?string,
     *     street_address?: ?string,
     *     payment_method?: ?string,
     *     bank_name?: ?string,
     *     notes?: ?string,
     *     design_notes?: ?string,
     *     design_file_path?: ?string,
     * }  $customerData
     * @param  array{
     *     product_id?: ?int,
     *     quantity: int,
     *     price?: ?float,
     * }  $onDemandItem
     */
    public function createOnDemandPurchase(array $customerData, array $onDemandItem): Purchase
    {
        $quantity = max(1, (int) ($onDemandItem['quantity'] ?? 1));

        return DB::transaction(function () use ($customerData, $onDemandItem, $quantity) {
            $orderCode = 'POD-'.now()->format('Ymd').'-'.strtoupper(Str::random(5));

            $product = null;
            $unitPrice = 0.0;

            if (! empty($onDemandItem['product_id'])) {
                /** @var Product $product */
                $product = $this->productService->getDataProduct($onDemandItem['product_id']);
                $unitPrice = isset($onDemandItem['price']) ? (float) $onDemandItem['price'] : (float) $product->price;
            } elseif (isset($onDemandItem['price'])) {
                $unitPrice = (float) $onDemandItem['price'];
            }

            $totalPrice = $unitPrice * $quantity;
            $address = $customerData['address'] ?? $customerData['customer_address'] ?? null;

            $purchase = Purchase::create([
                'user_id' => $customerData['user_id'] ?? null,
                'order_code' => $orderCode,
                'order_type' => 'on_demand',
                'customer_name' => trim($customerData['customer_name']),
                'customer_phone' => trim($customerData['customer_phone']),
                'customer_address' => $address,
                'address' => $address,
                'city' => $customerData['city'] ?? null,
                'district' => $customerData['district'] ?? null,
                'village' => $customerData['village'] ?? null,
                'street_address' => $customerData['street_address'] ?? null,
                'payment_method' => $customerData['payment_method'] ?? 'qris',
                'bank_name' => ($customerData['payment_method'] ?? 'qris') === 'bank_transfer'
                    ? ($customerData['bank_name'] ?? 'BCA')
                    : null,
                'total_price' => $totalPrice,
                'status' => 'waiting_approval',
                'notes' => $customerData['notes'] ?? null,
                'design_notes' => $customerData['design_notes'] ?? null,
                'design_file_path' => $customerData['design_file_path'] ?? null,
            ]);

            if ($product) {
                DetailPurchase::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $unitPrice,
                    'total_price' => $totalPrice,
                ]);
            }

            LogPurchase::create([
                'purchase_id' => $purchase->id,
                'action' => 'created_on_demand',
                'desc' => "Pesanan Print on Demand baru diajukan (Qty: {$quantity}) dan menunggu persetujuan admin.",
            ]);

            return $purchase->load(['user', 'detailPurchases.product', 'logPurchases']);
        });
    }

    /**
     * Approve a print-on-demand order, moving it to pending payment.
     */
    public function approveOnDemand(Purchase|int|string $purchase, ?float $adjustedPrice = null, ?string $adminNotes = null): Purchase
    {
        return DB::transaction(function () use ($purchase, $adjustedPrice, $adminNotes) {
            $order = $this->getPurchase($purchase);

            $updates = [
                'status' => 'pending',
            ];

            if ($adjustedPrice !== null && $adjustedPrice >= 0) {
                $updates['total_price'] = $adjustedPrice;
            }

            if ($adminNotes !== null) {
                $updates['admin_notes'] = $adminNotes;
            }

            $order->update($updates);

            LogPurchase::create([
                'purchase_id' => $order->id,
                'action' => 'on_demand_approved',
                'desc' => $adminNotes ? "Pesanan On Demand disetujui oleh admin. Catatan: {$adminNotes}" : 'Pesanan On Demand disetujui oleh admin, menunggu pembayaran.',
            ]);

            SiteStatsService::clearCache();

            return $order->fresh(['user', 'detailPurchases.product', 'logPurchases']);
        });
    }

    /**
     * Reject a print-on-demand order with reason notes.
     */
    public function rejectOnDemand(Purchase|int|string $purchase, string $reason): Purchase
    {
        return DB::transaction(function () use ($purchase, $reason) {
            $order = $this->getPurchase($purchase);

            $order->update([
                'status' => 'cancelled',
                'admin_notes' => $reason,
            ]);

            LogPurchase::create([
                'purchase_id' => $order->id,
                'action' => 'on_demand_rejected',
                'desc' => "Pesanan On Demand ditolak oleh admin. Alasan: {$reason}",
            ]);

            SiteStatsService::clearCache();

            return $order->fresh(['user', 'detailPurchases.product', 'logPurchases']);
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

            // Only regular stock purchases deduct/restore inventory
            if ($order->order_type !== 'on_demand') {
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
            'payment_method' => $order->payment_method ?: 'qris',
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
        $baseQris = $baseQris ?: app(PaymentSettingService::class)->getQrisBaseString();

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
