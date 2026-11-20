<?php

namespace App\Models;

use Database\Factories\UnboxingFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unboxing extends Model
{
    /** @use HasFactory<UnboxingFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'url',
        'platform',
        'thumbnail_path',
        'recorded_at',
        'is_visible',
        'description',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_visible' => 'boolean',
            'recorded_at' => 'date',
        ];
    }

    /**
     * Accessor for item_name (alias of title).
     */
    public function getItemNameAttribute(): string
    {
        return (string) ($this->attributes['title'] ?? '');
    }

    /**
     * Mutator for item_name (sets title).
     */
    public function setItemNameAttribute(?string $value): void
    {
        $this->attributes['title'] = (string) $value;
    }

    /**
     * Determine platform ('youtube', 'instagram', or 'tiktok') based on URL.
     */
    public static function determinePlatform(?string $url): string
    {
        if (! $url) {
            return 'youtube';
        }

        if (preg_match('/tiktok\.com/i', $url)) {
            return 'tiktok';
        }

        if (preg_match('/instagram\.com/i', $url)) {
            return 'instagram';
        }

        return 'youtube';
    }

    /**
     * Extract YouTube Video ID if platform is YouTube.
     */
    public function getYoutubeIdAttribute(): ?string
    {
        if ($this->platform !== 'youtube' || empty($this->url)) {
            return null;
        }

        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/\s]{11})%i', $this->url, $match)) {
            return $match[1];
        }

        return null;
    }

    /**
     * Accessor for thumbnail URL with precedence:
     * 1. Uploaded thumbnail_path
     * 2. YouTube official HQ thumbnail
     * 3. Null (will render clean text placeholder)
     */
    public function getThumbnailUrlAttribute(): ?string
    {
        if (! empty($this->thumbnail_path)) {
            return asset('storage/'.ltrim($this->thumbnail_path, '/'));
        }

        if ($this->platform === 'youtube' && $this->youtube_id) {
            return 'https://img.youtube.com/vi/'.$this->youtube_id.'/hqdefault.jpg';
        }

        return null;
    }

    /**
     * Display label for platform.
     */
    public function getPlatformLabelAttribute(): string
    {
        return match (strtolower((string) $this->platform)) {
            'instagram' => 'Instagram',
            'tiktok' => 'TikTok',
            default => 'YouTube',
        };
    }

    /**
     * Call to action text for platform.
     */
    public function getPlatformActionTextAttribute(): string
    {
        return match (strtolower((string) $this->platform)) {
            'instagram' => 'Tonton di Instagram',
            'tiktok' => 'Tonton di TikTok',
            default => 'Tonton di YouTube',
        };
    }

    /**
     * Scope query to only visible unboxings.
     *
     * @param  Builder<Unboxing>  $query
     * @return Builder<Unboxing>
     */
    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('is_visible', true);
    }
}
