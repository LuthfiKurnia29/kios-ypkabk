<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    protected $guarded = ['id'];

    /**
     * Disk used for hero image storage.
     * Change this constant to switch to a different disk (e.g. Supabase Storage).
     */
    public const HERO_IMAGE_DISK = 'public';

    /**
     * Directory within the disk where hero images are stored.
     */
    public const HERO_IMAGE_PATH = 'hero';

    private const CACHE_PREFIX = 'site_setting:';

    private const CACHE_TTL = 300;

    /**
     * Get a setting value by key with an optional default.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        return Cache::remember(
            self::CACHE_PREFIX.$key,
            self::CACHE_TTL,
            fn () => static::where('key', $key)->value('value') ?? $default
        );
    }

    /**
     * Set a setting value by key.
     */
    public static function set(string $key, ?string $value, string $type = 'string'): static
    {
        $setting = static::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'type' => $type]
        );

        Cache::forget(self::CACHE_PREFIX.$key);

        return $setting;
    }

    /**
     * Clean a raw hero title by converting legacy <span> tags to *asterisks*
     * and stripping any other HTML tags so only plain text with *word* remains.
     */
    public static function cleanHeroTitle(?string $title): string
    {
        if (! $title) {
            return 'Barang preloved, *peluang baru* untuk anak berkebutuhan khusus.';
        }

        // Convert legacy <span class="...">word</span> to *word*
        $converted = preg_replace('/<span[^>]*>(.*?)<\/span>/is', '*$1*', $title);

        // Strip any remaining HTML tags so form input is purely clean text
        return trim(strip_tags((string) $converted));
    }

    /**
     * Render the hero title for public Blade view:
     * Normalizes legacy span tags, escapes HTML for security, then converts *word* into <span class="text-primary">word</span>.
     */
    public static function renderHeroTitle(?string $title): string
    {
        if (! $title) {
            return 'Barang preloved, <span class="text-primary">peluang baru</span> untuk anak berkebutuhan khusus.';
        }

        // Convert any legacy <span class="...">word</span> to *word* first
        $normalized = preg_replace('/<span[^>]*>(.*?)<\/span>/is', '*$1*', $title);

        // Escape HTML entities to prevent XSS
        $escaped = e($normalized);

        // Convert *word* into <span class="text-primary">word</span>
        return preg_replace('/\*(.*?)\*/', '<span class="text-primary">$1</span>', $escaped);
    }

    /**
     * Clear all site-setting caches.
     */
    public static function clearCache(): void
    {
        $keys = static::pluck('key');

        foreach ($keys as $key) {
            Cache::forget(self::CACHE_PREFIX.$key);
        }
    }
}
