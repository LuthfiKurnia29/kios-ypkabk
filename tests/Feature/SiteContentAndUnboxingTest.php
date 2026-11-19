<?php

namespace Tests\Feature;

use App\Models\Unboxing;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SiteContentAndUnboxingTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->admin()->create();
    }

    public function test_katalog_page_renders_donation_modal_globally(): void
    {
        $response = $this->get(route('katalog'));

        $response->assertOk();
        $response->assertSee('id="donation-modal"', false);
        $response->assertSee('id="donation-photos-preview"', false);
        $response->assertSee('previewDonationPhotos', false);
    }

    public function test_admin_can_update_site_contact_and_social_settings(): void
    {
        $payload = [
            'footer_description' => 'Deskripsi khusus footer Kios Mandiri YPKABK.',
            'contact_whatsapp' => '081234567890',
            'contact_email' => 'halo@ypkabk-kios.org',
            'service_hours' => 'Senin - Jumat 09.00 - 17.00 WIB',
            'sentra_sidoarjo_name' => 'Sentra Sidoarjo Pusat',
            'sentra_sidoarjo_address' => 'Jl. Pahlawan Sidoarjo No. 45',
            'sentra_sidoarjo_maps_url' => 'https://maps.app.goo.gl/sidoarjo45',
            'sentra_mojokerto_name' => 'Sentra Mojokerto Cabang',
            'sentra_mojokerto_address' => 'Jl. Majapahit Mojokerto No. 88',
            'sentra_mojokerto_maps_url' => 'https://maps.app.goo.gl/mojokerto88',
            'social_instagram' => 'https://instagram.com/kios_ypkabk_official',
            'social_youtube' => 'https://youtube.com/@kios_ypkabk_official',
            'social_tiktok' => 'https://tiktok.com/@kios_ypkabk_official',
        ];

        $response = $this->actingAs($this->admin)->put(route('admin.site-content.update-info'), $payload);

        $response->assertRedirect(route('admin.site-content.edit'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('site_settings', [
            'key' => 'footer_description',
            'value' => 'Deskripsi khusus footer Kios Mandiri YPKABK.',
        ]);

        $this->assertDatabaseHas('site_settings', [
            'key' => 'contact_whatsapp',
            'value' => '081234567890',
        ]);

        // Verify public pages render updated settings
        $kontakResponse = $this->get(route('kontak'));
        $kontakResponse->assertOk();
        $kontakResponse->assertSee('Sentra Sidoarjo Pusat');
        $kontakResponse->assertSee('Jl. Pahlawan Sidoarjo No. 45');
        $kontakResponse->assertSee('halo@ypkabk-kios.org');
        $kontakResponse->assertSee('https://maps.app.goo.gl/sidoarjo45');
        $kontakResponse->assertSee('https://maps.app.goo.gl/mojokerto88');

        $homeResponse = $this->get(route('home'));
        $homeResponse->assertOk();
        $homeResponse->assertSee('Deskripsi khusus footer Kios Mandiri YPKABK.');
        $homeResponse->assertSee('https://instagram.com/kios_ypkabk_official');
        $homeResponse->assertSee('https://tiktok.com/@kios_ypkabk_official');
        $homeResponse->assertSee('wa.me/6281234567890');
    }

    public function test_admin_can_create_tiktok_unboxing_with_thumbnail_upload_and_renders_on_public_page(): void
    {
        Storage::fake('public');

        $thumbnail = UploadedFile::fake()->create('unboxing-thumb.jpg', 100, 'image/jpeg');

        $payload = [
            'title' => 'Unboxing Mainan Sensori ABK',
            'url' => 'https://www.tiktok.com/@kiosypkabk/video/7123456789012345678',
            'platform' => 'tiktok',
            'thumbnail' => $thumbnail,
            'recorded_at' => '2026-11-19',
            'is_visible' => '1',
            'description' => 'Dokumentasi paket kiriman mainan edukatif.',
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.unboxing.store'), $payload);

        $response->assertRedirect(route('admin.unboxing.index'));
        $response->assertSessionHas('success');

        $unboxing = Unboxing::where('title', 'Unboxing Mainan Sensori ABK')->firstOrFail();
        $this->assertSame('tiktok', $unboxing->platform);
        $this->assertNotNull($unboxing->thumbnail_path);

        Storage::disk('public')->assertExists($unboxing->thumbnail_path);

        // Verify public unboxing page
        $publicResponse = $this->get(route('unboxing'));
        $publicResponse->assertOk();
        $publicResponse->assertSee('Unboxing Mainan Sensori ABK');
        $publicResponse->assertSee('TikTok');
        $publicResponse->assertSee('Tonton di TikTok');
        $this->assertNotNull($unboxing->thumbnail_url);
        $publicResponse->assertSee($unboxing->thumbnail_path);
    }

    public function test_admin_can_update_unboxing_and_replace_thumbnail(): void
    {
        Storage::fake('public');

        $oldThumbnail = UploadedFile::fake()->create('old-thumb.jpg', 100, 'image/jpeg');
        $oldPath = $oldThumbnail->store('unboxing', 'public');

        $unboxing = Unboxing::create([
            'title' => 'Paket Donasi Perlengkapan Mandi',
            'url' => 'https://www.instagram.com/reel/Cx123456789/',
            'platform' => 'instagram',
            'thumbnail_path' => $oldPath,
            'recorded_at' => '2026-11-10',
            'is_visible' => true,
        ]);

        Storage::disk('public')->assertExists($oldPath);

        $newThumbnail = UploadedFile::fake()->create('new-thumb.png', 100, 'image/png');

        $updatePayload = [
            'title' => 'Paket Donasi Perlengkapan Terapi',
            'url' => 'https://www.tiktok.com/@kiosypkabk/video/987654321',
            'platform' => 'tiktok',
            'thumbnail' => $newThumbnail,
            'recorded_at' => '2026-11-15',
            'is_visible' => '1',
        ];

        $response = $this->actingAs($this->admin)->put(route('admin.unboxing.update', $unboxing->id), $updatePayload);

        $response->assertRedirect(route('admin.unboxing.index'));

        $unboxing->refresh();
        $this->assertSame('Paket Donasi Perlengkapan Terapi', $unboxing->title);
        $this->assertSame('tiktok', $unboxing->platform);
        $this->assertNotSame($oldPath, $unboxing->thumbnail_path);

        Storage::disk('public')->assertMissing($oldPath);
        Storage::disk('public')->assertExists($unboxing->thumbnail_path);
    }
}
