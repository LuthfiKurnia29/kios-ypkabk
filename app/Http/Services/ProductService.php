<?php

namespace App\Http\Services;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class ProductService extends BaseService
{
    /**
     * Retrieve products with optional filters, sorting, and pagination.
     *
     * @param  array{
     *     search?: ?string,
     *     q?: ?string,
     *     category_id?: int|string|null,
     *     category?: ?string,
     *     category_slug?: ?string,
     *     source?: ?string,
     *     condition?: string|array<string>|null,
     *     status?: ?string,
     *     is_featured?: ?bool,
     *     only_available?: ?bool,
     *     min_price?: ?float,
     *     max_price?: ?float,
     *     price_range?: ?string,
     *     sort?: ?string,
     * }  $filters
     * @return LengthAwarePaginator<Product>|Collection<int, Product>
     */
    public function getProducts(array $filters = [], ?int $perPage = null): LengthAwarePaginator|Collection
    {
        $query = Product::with('category');

        // Search text
        $search = trim((string) ($filters['search'] ?? $filters['q'] ?? ''));
        if ($search !== '') {
            $query->where(function (Builder $q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('artisan_name', 'like', "%{$search}%");
            });
        }

        // Category by ID
        if (! empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        // Category by Slug
        $categorySlug = $filters['category_slug'] ?? $filters['category'] ?? null;
        if (! empty($categorySlug) && $categorySlug !== 'all') {
            $query->whereHas('category', function (Builder $q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        // Source ('karya' / 'preloved')
        if (! empty($filters['source'])) {
            $query->where('source', $filters['source']);
        }

        // Condition ('baru' / 'mulus' / 'layak')
        if (! empty($filters['condition'])) {
            $conditions = is_array($filters['condition']) ? $filters['condition'] : [$filters['condition']];
            $query->whereIn('condition', $conditions);
        }

        // Status
        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Featured flag
        if (isset($filters['is_featured'])) {
            $query->where('is_featured', (bool) $filters['is_featured']);
        }

        // Availability (stock > 0 and not marked empty)
        if (! empty($filters['only_available'])) {
            $query->available();
        }

        // Price boundaries
        if (isset($filters['min_price']) && is_numeric($filters['min_price'])) {
            $query->where('price', '>=', (float) $filters['min_price']);
        }
        if (isset($filters['max_price']) && is_numeric($filters['max_price'])) {
            $query->where('price', '<=', (float) $filters['max_price']);
        }

        // Catalog price brackets
        $priceRange = $filters['price_range'] ?? null;
        if ($priceRange === 'under-30') {
            $query->where('price', '<', 30000);
        } elseif ($priceRange === '30-75') {
            $query->whereBetween('price', [30000, 75000]);
        } elseif ($priceRange === 'over-75') {
            $query->where('price', '>', 75000);
        }

        // Sorting
        $sort = $filters['sort'] ?? null;
        if ($sort === 'termurah') {
            $query->orderBy('price', 'asc');
        } elseif ($sort === 'termahal') {
            $query->orderBy('price', 'desc');
        } elseif ($sort === 'stok') {
            $query->orderBy('stock', 'desc');
        } else {
            $query->latest();
        }

        return $perPage !== null ? $query->paginate($perPage) : $query->get();
    }

    /**
     * Retrieve a single product by instance, ID, or name.
     */
    public function getDataProduct(Product|int|string $product): Product
    {
        if ($product instanceof Product) {
            return $product->loadMissing('category');
        }

        if (is_numeric($product)) {
            return Product::with('category')->findOrFail((int) $product);
        }

        return Product::with('category')
            ->where('id', $product)
            ->orWhere('name', $product)
            ->firstOrFail();
    }

    /**
     * Create a new product record.
     *
     * @param  array<string, mixed>  $data
     */
    public function createDataProduct(array $data): Product
    {
        $normalized = $this->normalizeProductData($data);

        $product = Product::create($normalized);

        return $product->loadMissing('category');
    }

    /**
     * Update an existing product record.
     *
     * @param  array<string, mixed>  $data
     */
    public function updateDataProduct(Product|int|string $product, array $data): Product
    {
        $targetProduct = $this->getDataProduct($product);
        $normalized = $this->normalizeProductData($data, $targetProduct);

        $targetProduct->update($normalized);

        return $targetProduct->fresh('category');
    }

    /**
     * Delete a product record.
     */
    public function deleteDataProduct(Product|int|string $product): bool
    {
        $targetProduct = $this->getDataProduct($product);

        return (bool) $targetProduct->delete();
    }

    /**
     * Increment or decrement product stock and automatically refresh status.
     *
     * @param  int  $quantityChange  Positive to add, negative to subtract
     */
    public function updateStock(Product|int|string $product, int $quantityChange): Product
    {
        $targetProduct = $this->getDataProduct($product);
        $newStock = max(0, ((int) $targetProduct->stock) + $quantityChange);

        return $this->updateDataProduct($targetProduct, [
            'stock' => $newStock,
        ]);
    }

    /**
     * Get featured products available for purchase.
     *
     * @return Collection<int, Product>
     */
    public function getFeaturedProducts(int $limit = 4): Collection
    {
        return Product::with('category')
            ->featured()
            ->available()
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Get all currently available products in stock.
     *
     * @return Collection<int, Product>
     */
    public function getAvailableProducts(): Collection
    {
        return Product::with('category')
            ->available()
            ->latest()
            ->get();
    }

    /**
     * Normalize product data and automatically determine status and isEmpty based on stock.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function normalizeProductData(array $data, ?Product $existing = null): array
    {
        $stock = isset($data['stock'])
            ? (int) $data['stock']
            : ($existing ? (int) $existing->stock : 0);

        if ($stock < 0) {
            $stock = 0;
        }
        $data['stock'] = $stock;

        if (! isset($data['status'])) {
            if ($stock <= 0) {
                $data['status'] = 'empty';
            } elseif ($stock <= 3) {
                $data['status'] = 'minimal_stock';
            } else {
                $data['status'] = 'ready';
            }
        }

        if (! isset($data['isEmpty'])) {
            $data['isEmpty'] = ($stock <= 0 || $data['status'] === 'empty');
        }

        return $data;
    }
}
