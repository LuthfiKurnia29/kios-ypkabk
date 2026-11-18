<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Donation extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'item_count' => 'integer',
            'received_at' => 'datetime',
        ];
    }

    /**
     * Category associated with the donation.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Review submitted for this donation.
     */
    public function review(): HasOne
    {
        return $this->hasOne(Review::class);
    }

    /**
     * Get or generate the review invitation URL for this donation.
     * Only valid for received donations.
     */
    public function reviewUrl(): ?string
    {
        if ($this->status !== 'received') {
            return null;
        }

        if (empty($this->review_token)) {
            $this->review_token = Str::random(40);
            $this->saveQuietly();
        }

        return route('reviews.create', ['token' => $this->review_token]);
    }
}
