<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'stock' => 'integer',
            'isEmpty' => 'boolean',
            'is_featured' => 'boolean',
        ];
    }

    /**
     * Category of the product.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Order items containing this product.
     */
    public function detailPurchases(): HasMany
    {
        return $this->hasMany(DetailPurchase::class);
    }

    /**
     * Scope a query to only include featured products.
     */
    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope a query to only include ready products in stock.
     */
    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where('stock', '>', 0)->where('status', '!=', 'empty');
    }
}
