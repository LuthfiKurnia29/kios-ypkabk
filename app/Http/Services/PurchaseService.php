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
}
