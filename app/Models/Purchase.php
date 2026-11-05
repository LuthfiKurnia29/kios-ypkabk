<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
            'paid_at' => 'datetime',
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
}
