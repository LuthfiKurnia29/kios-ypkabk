<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Purchase extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'total_price' => 'decimal:2',
        ];
    }

    /**
     * User who made the purchase (if registered).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Detail items in this purchase.
     */
    public function detailPurchases(): HasMany
    {
        return $this->hasMany(DetailPurchase::class);
    }

    /**
     * Audit logs for this purchase.
     */
    public function logPurchases(): HasMany
    {
        return $this->hasMany(LogPurchase::class);
    }

    /**
     * Review submitted for this purchase.
     */
    public function review(): HasOne
    {
        return $this->hasOne(Review::class);
    }

    /**
     * Get or generate the review invitation URL for this purchase.
     * Only valid for paid or completed orders.
     */
    public function reviewUrl(): ?string
    {
        if (! in_array($this->status, ['paid', 'completed'], true)) {
            return null;
        }

        if (empty($this->review_token)) {
            $this->review_token = Str::random(40);
            $this->saveQuietly();
        }

        return route('reviews.create', ['token' => $this->review_token]);
    }
}
