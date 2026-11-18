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
     * Determine platform ('youtube' or 'instagram') based on URL.
     */
    public static function determinePlatform(?string $url): string
    {
        if (! $url) {
            return 'youtube';
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
