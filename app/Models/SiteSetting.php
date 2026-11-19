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

    public const ALL_CACHE_KEY = 'site_settings_all';

    private const CACHE_PREFIX = 'site_setting:';

    private const CACHE_TTL = 3600;

    /**
     * Per-request memoization of public settings to avoid repeated queries.
     *
     * @var array<string, string>|null
     */
    private static ?array $publicSettingsCache = null;

    /**
     * Get all settings as key-value pairs (cached for 1 hour per requirement).
     *
     * @return array<string, mixed>
     */
    public static function getAllSettings(): array
    {
        return Cache::remember('site_settings_all', 3600, function () {
            try {
                return static::pluck('value', 'key')->toArray();
            } catch (\Throwable) {
                return [];
            }
        });
    }

    /**
     * Get a setting value by key with an optional default.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $all = static::getAllSettings();

        return array_key_exists($key, $all) && $all[$key] !== null ? $all[$key] : $default;
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
        Cache::forget('site_settings_all');
        Cache::forget(self::ALL_CACHE_KEY);
        Cache::forget('site_settings_all');
        Cache::forget('site_settings:all_keyed');
        static::resetPublicSettingsCache();

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
        try {
            $keys = static::pluck('key');

            foreach ($keys as $key) {
                Cache::forget(self::CACHE_PREFIX.$key);
            }
        } catch (\Throwable) {
        }

        Cache::forget(self::ALL_CACHE_KEY);
        Cache::forget('site_settings_all');
        Cache::forget('site_settings:all_keyed');
        static::resetPublicSettingsCache();
    }

    /**
     * Format a phone number to international WhatsApp format (628...).
     */
    public static function formatWhatsappNumber(?string $phone): string
    {
        $cleaned = preg_replace('/\D+/', '', (string) $phone);

        if (str_starts_with($cleaned, '0')) {
            return '62'.substr($cleaned, 1);
        }

        if (str_starts_with($cleaned, '8')) {
            return '62'.$cleaned;
        }

        return $cleaned ?: '6281234567890';
    }

    /**
     * Get all public site settings with fallbacks.
     * Result is memoized for the current request to avoid repeated queries.
     *
     * @return array<string, string>
     */
    public static function getPublicSettings(): array
    {
        if (static::$publicSettingsCache !== null) {
            return static::$publicSettingsCache;
        }

        $rawWa = static::get('contact_whatsapp', '081234567890');
        $formattedWa = static::formatWhatsappNumber($rawWa);

        static::$publicSettingsCache = [
            'footer_description' => static::get(
                'footer_description',
                'Toko amal dan galeri karya anak berkebutuhan khusus. 100% donasi dan hasil transaksi menopang ruang terapi dan vokasi berdaya di Jawa Timur.'
            ),
            'contact_whatsapp' => $rawWa,
            'contact_whatsapp_formatted' => $formattedWa,
            'contact_whatsapp_link' => 'https://wa.me/'.$formattedWa,
            'contact_email' => static::get('contact_email', 'kios@ypkabk.org'),
            'service_hours' => static::get('service_hours', 'Senin – Sabtu: 08.00 – 16.00 WIB'),
            'sentra_sidoarjo_name' => static::get('sentra_sidoarjo_name', 'Sentra Kios Mandiri Sidoarjo'),
            'sentra_sidoarjo_address' => static::get('sentra_sidoarjo_address', 'Jl. Wonocolo Pabrik Kulit No. 34, Sepanjang, Sidoarjo, Jawa Timur 61256'),
            'sentra_sidoarjo_maps_url' => static::get('sentra_sidoarjo_maps_url', 'https://maps.google.com/?q=Sentra+Kios+Mandiri+YPKABK+Sidoarjo'),
            'sentra_mojokerto_name' => static::get('sentra_mojokerto_name', 'Sentra Kios Mandiri Mojokerto'),
            'sentra_mojokerto_address' => static::get('sentra_mojokerto_address', 'Jl. Raya Brangkal No. 12, Sooko, Mojokerto, Jawa Timur 61318'),
            'sentra_mojokerto_maps_url' => static::get('sentra_mojokerto_maps_url', 'https://maps.google.com/?q=Sentra+Kios+Mandiri+YPKABK+Mojokerto'),
            'social_instagram' => static::get('social_instagram', 'https://instagram.com/kiosmandiri.ypkabk'),
            'social_youtube' => static::get('social_youtube', 'https://youtube.com/@ypkabk'),
            'social_tiktok' => static::get('social_tiktok', 'https://tiktok.com/@kiosmandiri.ypkabk'),
        ];

        return static::$publicSettingsCache;
    }

    /**
     * Reset the per-request memoized public settings.
     * Called after settings are updated so the next read fetches fresh data.
     */
    public static function resetPublicSettingsCache(): void
    {
        static::$publicSettingsCache = null;
    }
}
