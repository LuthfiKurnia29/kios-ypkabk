<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateSiteContentRequest;
use App\Http\Services\SiteStatsService;
use App\Models\Review;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SiteContentController extends Controller
{
    /**
     * Show the site-content editor (Hero, ABK Terlatih).
     */
    public function edit(): View
    {
        $settings = SiteSetting::pluck('value', 'key');
        $heroImageUrl = self::resolveHeroImageUrl($settings->get('hero_image'));

        return view('admin.pages.site-content', compact(
            'settings',
            'heroImageUrl'
        ));
    }

    /**
     * Update hero section settings.
     */
    public function updateHero(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'hero_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'hero_image_alt' => ['nullable', 'string', 'max:255'],
            'hero_title' => ['nullable', 'string', 'max:500'],
            'hero_subtitle' => ['nullable', 'string', 'max:1000'],
        ], [
            'hero_image.image' => 'File hero harus berupa gambar.',
            'hero_image.mimes' => 'Format gambar hero harus JPG, PNG, atau WebP.',
            'hero_image.max' => 'Ukuran gambar hero maksimal 2 MB.',
            'hero_image_alt.max' => 'Teks alternatif gambar maksimal 255 karakter.',
            'hero_title.max' => 'Judul hero maksimal 500 karakter.',
            'hero_subtitle.max' => 'Subjudul hero maksimal 1000 karakter.',
        ]);

        if ($request->hasFile('hero_image')) {
            $oldPath = SiteSetting::get('hero_image');

            if ($oldPath) {
                Storage::disk(SiteSetting::HERO_IMAGE_DISK)->delete($oldPath);
            }

            $path = $request->file('hero_image')->store(
                SiteSetting::HERO_IMAGE_PATH,
                SiteSetting::HERO_IMAGE_DISK
            );

            SiteSetting::set('hero_image', $path, 'image');
        }

        foreach (['hero_image_alt', 'hero_title', 'hero_subtitle'] as $key) {
            if ($request->has($key)) {
                $val = $validated[$key] ?? $request->input($key);
                if ($key === 'hero_title') {
                    $val = SiteSetting::cleanHeroTitle($val);
                }
                SiteSetting::set($key, $val);
            }
        }

        SiteSetting::clearCache();
        Cache::forget('site_settings_all');

        return redirect()->route('admin.site-content.edit')
            ->with('success_hero', 'Pengaturan Hero berhasil disimpan.');
    }

    /**
     * Update ABK trained count setting.
     */
    public function updateAbk(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'abk_trained_count' => ['required', 'integer', 'min:0'],
        ], [
            'abk_trained_count.required' => 'Jumlah ABK binaan mandiri wajib diisi.',
            'abk_trained_count.integer' => 'Jumlah ABK terlatih harus berupa bilangan bulat.',
            'abk_trained_count.min' => 'Jumlah ABK terlatih tidak boleh negatif.',
        ]);

        SiteSetting::set('abk_trained_count', (string) $validated['abk_trained_count'], 'number');
        SiteSetting::clearCache();
        Cache::forget('site_settings_all');
        SiteStatsService::clearCache();

        return redirect()->route('admin.site-content.edit')
            ->with('success_abk', 'Data jumlah ABK terlatih mandiri berhasil disimpan.');
    }

    /**
     * Update hero settings and/or abk_trained_count (generic/fallback).
     */
    public function update(UpdateSiteContentRequest $request): RedirectResponse
    {
        if ($request->hasFile('hero_image')) {
            $oldPath = SiteSetting::get('hero_image');

            if ($oldPath) {
                Storage::disk(SiteSetting::HERO_IMAGE_DISK)->delete($oldPath);
            }

            $path = $request->file('hero_image')->store(
                SiteSetting::HERO_IMAGE_PATH,
                SiteSetting::HERO_IMAGE_DISK
            );

            SiteSetting::set('hero_image', $path, 'image');
        }

        foreach (['hero_image_alt', 'hero_title', 'hero_subtitle'] as $key) {
            if ($request->has($key)) {
                $val = $request->input($key);
                if ($key === 'hero_title') {
                    $val = SiteSetting::cleanHeroTitle($val);
                }
                SiteSetting::set($key, $val);
            }
        }

        if ($request->has('abk_trained_count')) {
            SiteSetting::set('abk_trained_count', (string) $request->integer('abk_trained_count', 0), 'number');
            SiteStatsService::clearCache();
        }

        $infoKeys = [
            'footer_description',
            'contact_whatsapp',
            'contact_email',
            'service_hours',
            'sentra_sidoarjo_name',
            'sentra_sidoarjo_address',
            'sentra_sidoarjo_maps_url',
            'sentra_mojokerto_name',
            'sentra_mojokerto_address',
            'sentra_mojokerto_maps_url',
            'social_instagram',
            'social_youtube',
            'social_tiktok',
        ];

        foreach ($infoKeys as $key) {
            if ($request->has($key)) {
                SiteSetting::set($key, $request->input($key));
            }
        }

        SiteSetting::clearCache();
        Cache::forget('site_settings_all');

        return redirect()->route('admin.site-content.edit')
            ->with('success', 'Konten situs berhasil diperbarui.');
    }

    /**
     * Update contact, center, social media, and footer settings.
     */
    public function updateInfo(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'footer_description' => ['nullable', 'string', 'max:1000'],
            'contact_whatsapp' => ['nullable', 'string', 'max:50'],
            'contact_email' => ['nullable', 'string', 'max:100'],
            'service_hours' => ['nullable', 'string', 'max:150'],
            'sentra_sidoarjo_name' => ['nullable', 'string', 'max:100'],
            'sentra_sidoarjo_address' => ['nullable', 'string', 'max:500'],
            'sentra_sidoarjo_maps_url' => ['nullable', 'string', 'max:500'],
            'sentra_mojokerto_name' => ['nullable', 'string', 'max:100'],
            'sentra_mojokerto_address' => ['nullable', 'string', 'max:500'],
            'sentra_mojokerto_maps_url' => ['nullable', 'string', 'max:500'],
            'social_instagram' => ['nullable', 'string', 'max:255'],
            'social_youtube' => ['nullable', 'string', 'max:255'],
            'social_tiktok' => ['nullable', 'string', 'max:255'],
        ]);

        foreach ($validated as $key => $value) {
            SiteSetting::set($key, $value);
        }

        SiteSetting::clearCache();
        Cache::forget('site_settings_all');

        return redirect()->route('admin.site-content.edit')
            ->with('success', 'Pengaturan kontak, sentra, dan footer berhasil disimpan.');
    }

    // ─── Reviews Management (Curated) ───────────────────────────────

    /**
     * Display listing of reviews with tabs filter.
     */
    public function reviewsIndex(Request $request): View
    {
        $tab = $request->query('tab', 'all');

        $query = Review::with(['purchase', 'donation'])->latest();

        if ($tab === 'pending') {
            $query->where('is_active', false);
        } elseif ($tab === 'active') {
            $query->where('is_active', true);
        }

        $reviews = $query->get();

        $counts = [
            'all' => Review::count(),
            'pending' => Review::where('is_active', false)->count(),
            'active' => Review::where('is_active', true)->count(),
        ];

        $hasMoreThanSixActive = $counts['active'] > 6;

        return view('admin.pages.reviews', compact('reviews', 'tab', 'counts', 'hasMoreThanSixActive'));
    }

    /**
     * Toggle active status of a review.
     */
    public function toggleReview(Review $review): RedirectResponse
    {
        $review->update(['is_active' => ! $review->is_active]);

        $status = $review->is_active ? 'ditampilkan di beranda' : 'disembunyikan dari beranda';

        return redirect()->back()
            ->with('success', "Ulasan dari {$review->name} berhasil {$status}.");
    }

    /**
     * Update review display order.
     */
    public function updateReviewOrder(Request $request, Review $review): RedirectResponse
    {
        $validated = $request->validate([
            'order' => ['required', 'integer', 'min:0'],
        ]);

        $review->update(['order' => $validated['order']]);

        return redirect()->back()
            ->with('success', "Urutan ulasan dari {$review->name} berhasil diperbarui.");
    }

    /**
     * Delete a review (spam curation).
     */
    public function destroyReview(Review $review): RedirectResponse
    {
        $name = $review->name;
        $review->delete();

        return redirect()->back()
            ->with('success', "Ulasan dari {$name} berhasil dihapus.");
    }

    // ─── Helpers ────────────────────────────────────────────────────

    /**
     * Resolve the public URL for the hero image (with fallback).
     */
    public static function resolveHeroImageUrl(?string $path): string
    {
        if ($path && Storage::disk(SiteSetting::HERO_IMAGE_DISK)->exists($path)) {
            return Storage::disk(SiteSetting::HERO_IMAGE_DISK)->url($path);
        }

        return asset('images/pict-1.webp');
    }
}
