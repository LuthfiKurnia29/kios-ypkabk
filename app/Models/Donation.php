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

    protected $fillable = [
        'donor_name',
        'donor_phone',
        'donor_city',
        'category_id',
        'category_name',
        'item_count',
        'item_photo_path',
        'item_photos',
        'description',
        'status',
        'admin_notes',
        'received_at',
        'review_token',
        'created_at',
        'updated_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'item_count' => 'integer',
            'received_at' => 'datetime',
            'item_photos' => 'array',
        ];
    }

    /**
     * Get the public URL for the primary donated item photo.
     */
    public function getItemPhotoUrlAttribute(): ?string
    {
        if (! empty($this->item_photos) && is_array($this->item_photos) && count($this->item_photos) > 0) {
            return asset('storage/'.$this->item_photos[0]);
        }

        return $this->item_photo_path ? asset('storage/'.$this->item_photo_path) : null;
    }

    /**
     * Get all public URLs for donated item photos.
     *
     * @return array<int, string>
     */
    public function getItemPhotosUrlsAttribute(): array
    {
        $urls = [];

        if (! empty($this->item_photos) && is_array($this->item_photos)) {
            foreach ($this->item_photos as $path) {
                if ($path) {
                    $urls[] = asset('storage/'.$path);
                }
            }
        }

        if (empty($urls) && $this->item_photo_path) {
            $urls[] = asset('storage/'.$this->item_photo_path);
        }

        return $urls;
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
