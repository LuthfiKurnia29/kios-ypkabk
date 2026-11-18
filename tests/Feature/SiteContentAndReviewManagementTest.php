<?php

namespace Tests\Feature;

use App\Http\Services\DonationService;
use App\Http\Services\PurchaseService;
use App\Http\Services\SiteStatsService;
use App\Models\Category;
use App\Models\DetailPurchase;
use App\Models\Donation;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Review;
use App\Models\SiteSetting;
use App\Models\User;
use App\Support\ReviewRequestLink;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SiteContentAndReviewManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->admin()->create();
        SiteStatsService::clearCache();
    }

    /**
     * 1. Halaman admin hanya bisa diakses admin (guest dan user biasa ditolak).
     */
    public function test_guest_and_regular_user_cannot_access_site_content_or_reviews(): void
    {
        // Guest dialihkan ke login
        $this->get(route('admin.site-content.edit'))->assertRedirect(route('login'));
        $this->get(route('admin.reviews.index'))->assertRedirect(route('login'));

        // User biasa ditolak (403 Forbidden)
        $regularUser = User::factory()->create(['role' => 'user']);
        $this->actingAs($regularUser)->get(route('admin.site-content.edit'))->assertForbidden();
        $this->actingAs($regularUser)->get(route('admin.reviews.index'))->assertForbidden();

        // Admin dapat mengakses (200 OK)
        $this->actingAs($this->admin)->get(route('admin.site-content.edit'))->assertOk();
        $this->actingAs($this->admin)->get(route('admin.reviews.index'))->assertOk();
    }

    /**
     * 2. Form terpisah admin: Hero menyimpan bagiannya dengan flash message sendiri.
     */
    public function test_admin_separate_form_hero_updates_and_validates(): void
    {
        Storage::fake(SiteSetting::HERO_IMAGE_DISK);

        $validImage = UploadedFile::fake()->create('hero-test.webp', 800, 'image/webp');

        $response = $this->actingAs($this->admin)->put(route('admin.site-content.update-hero'), [
            'hero_image' => $validImage,
            'hero_image_alt' => 'Alt Teks Hero Baru',
            'hero_title' => 'Barang preloved, *harapan baru* bagi sesama.',
            'hero_subtitle' => 'Subjudul hero mandiri.',
        ]);

        $response->assertRedirect(route('admin.site-content.edit'));
        $response->assertSessionHas('success_hero');

        $this->assertEquals('Alt Teks Hero Baru', SiteSetting::get('hero_image_alt'));
        $this->assertEquals('Barang preloved, *harapan baru* bagi sesama.', SiteSetting::get('hero_title'));
        $this->assertEquals('Subjudul hero mandiri.', SiteSetting::get('hero_subtitle'));
    }

    /**
     * 3. Form terpisah admin: Jumlah ABK menyimpan bagiannya dengan flash message sendiri dan validasi mandiri.
     */
    public function test_admin_separate_form_abk_updates_and_validates(): void
    {
        // Validasi negatif ditolak
        $responseInvalid = $this->actingAs($this->admin)->put(route('admin.site-content.update-abk'), [
            'abk_trained_count' => -5,
        ]);
        $responseInvalid->assertSessionHasErrors('abk_trained_count');

        // Validasi nilai valid
        $responseValid = $this->actingAs($this->admin)->put(route('admin.site-content.update-abk'), [
            'abk_trained_count' => 52,
        ]);

        $responseValid->assertRedirect(route('admin.site-content.edit'));
        $responseValid->assertSessionHas('success_abk');

        $this->assertEquals('52', SiteSetting::get('abk_trained_count'));

        // Angka tampil di beranda
        $this->get(route('home'))->assertOk()->assertSee('52');
    }

    /**
     * 4. Judul hero mengonversi *bintang* menjadi span primary dan meng-escape HTML.
     */
    public function test_hero_title_converts_asterisks_to_primary_color_and_escapes_html(): void
    {
        SiteSetting::set('hero_title', 'Barang preloved, *peluang baru* untuk <script>alert("xss")</script> anak.');

        $home = $this->get(route('home'));
        $home->assertOk();

        // Teks dalam bintang dikonversi ke span text-primary
        $home->assertSee('<span class="text-primary">peluang baru</span>', false);

        // Tag script harus di-escape sehingga tidak dieksekusi browser
        $home->assertDontSee('<script>alert("xss")</script>', false);
        $home->assertSee('&lt;script&gt;', false);

        // Jika database masih berisi format lama dengan <span>, tidak boleh bocor sebagai teks mentah
        SiteSetting::set('hero_title', 'Barang preloved, <span class="text-primary">peluang baru</span> untuk anak.');
        $homeLegacy = $this->get(route('home'));
        $homeLegacy->assertOk();
        $homeLegacy->assertSee('<span class="text-primary">peluang baru</span>', false);
        $homeLegacy->assertDontSee('&lt;span', false);
    }

    /**
     * 5. Upload gambar hero non-gambar atau > 2 MB ditolak.
     */
    public function test_hero_image_upload_rejects_non_image_or_file_exceeding_2mb(): void
    {
        Storage::fake(SiteSetting::HERO_IMAGE_DISK);

        // File non-gambar (.pdf)
        $pdfFile = UploadedFile::fake()->create('document.pdf', 500, 'application/pdf');
        $responsePdf = $this->actingAs($this->admin)->put(route('admin.site-content.update-hero'), [
            'hero_image' => $pdfFile,
        ]);
        $responsePdf->assertSessionHasErrors('hero_image');

        // File gambar > 2 MB (2500 KB)
        $largeImage = UploadedFile::fake()->create('large-hero.jpg', 2500, 'image/jpeg');
        $responseLarge = $this->actingAs($this->admin)->put(route('admin.site-content.update-hero'), [
            'hero_image' => $largeImage,
        ]);
        $responseLarge->assertSessionHasErrors('hero_image');
    }

    /**
     * 6. Beranda dan /dampak tetap tampil normal saat data database kosong.
     */
    public function test_home_and_dampak_pages_render_normally_when_data_is_empty(): void
    {
        SiteSetting::query()->delete();
        Donation::query()->delete();
        Purchase::query()->delete();
        Review::query()->delete();
        SiteStatsService::clearCache();

        // Beranda
        $homeResponse = $this->get(route('home'));
        $homeResponse->assertOk();
        $homeResponse->assertSee('Barang tersalurkan');
        $homeResponse->assertSee('Terlatih mandiri');
        $homeResponse->assertSee('Dana terapi terkumpul');
        $homeResponse->assertSee('Rp 0');
        $homeResponse->assertSee('Ulasan dikirim lewat WhatsApp setelah transaksi selesai');

        // /dampak
        $dampakResponse = $this->get(route('dampak'));
        $dampakResponse->assertOk();
        $dampakResponse->assertSee('Dampak & Transparansi');
        $dampakResponse->assertSee('Barang tersalurkan');
        $dampakResponse->assertSee('Terlatih mandiri');
        $dampakResponse->assertSee('Dana terapi terkumpul');
    }

    /**
     * 7. Dana terapi dan barang tersalurkan berubah mengikuti status pesanan dan donasi secara real-time.
     */
    public function test_therapy_funds_and_distributed_items_follow_order_and_donation_status(): void
    {
        $statsService = app(SiteStatsService::class);
        $donationService = app(DonationService::class);
        $purchaseService = app(PurchaseService::class);

        // Donasi pending (10 pcs) belum dihitung
        $donation = Donation::create([
            'donor_name' => 'Donatur Test',
            'donor_phone' => '08123456789',
            'donor_city' => 'Surabaya',
            'item_count' => 10,
            'status' => 'pending',
        ]);

        SiteStatsService::clearCache();
        $stats = $statsService->getStats();
        $this->assertEquals(0, $stats['barang_tersalurkan']);
        $this->assertEquals(0, $stats['dana_terapi']);

        // Donasi received dihitung
        $donationService->updateDonationStatus($donation, 'received', 10);
        SiteStatsService::clearCache();
        $stats = $statsService->getStats();
        $this->assertEquals(10, $stats['barang_tersalurkan']);

        // Purchase pending belum dihitung
        $category = Category::create(['name' => 'Kriya', 'slug' => 'kriya', 'is_active' => true]);
        $product = Product::create([
            'name' => 'Dompet Perca',
            'category_id' => $category->id,
            'price' => 50000,
            'stock' => 10,
            'status' => 'ready',
            'isEmpty' => false,
            'image' => 'sample.jpg',
        ]);

        $purchase = Purchase::create([
            'order_code' => 'ORD-TEST-100',
            'customer_name' => 'Pembeli Test',
            'customer_phone' => '08198765432',
            'total_price' => 150000,
            'status' => 'pending',
        ]);
        DetailPurchase::create([
            'purchase_id' => $purchase->id,
            'product_id' => $product->id,
            'quantity' => 3,
            'price' => 50000,
            'total_price' => 150000,
        ]);

        SiteStatsService::clearCache();
        $stats = $statsService->getStats();
        $this->assertEquals(10, $stats['barang_tersalurkan']);
        $this->assertEquals(0, $stats['dana_terapi']);

        // Purchase paid dihitung
        $purchaseService->updateStatus($purchase, 'paid');
        SiteStatsService::clearCache();
        $stats = $statsService->getStats();
        $this->assertEquals(150000, $stats['dana_terapi']);
        $this->assertEquals(13, $stats['barang_tersalurkan']);

        // Purchase cancelled dikurangi
        $purchaseService->updateStatus($purchase, 'cancelled');
        SiteStatsService::clearCache();
        $stats = $statsService->getStats();
        $this->assertEquals(0, $stats['dana_terapi']);
        $this->assertEquals(10, $stats['barang_tersalurkan']);
    }

    /**
     * 8. Method reviewUrl() pada model Purchase & Donation dan ReviewRequestLink.
     */
    public function test_review_url_generation_on_models_and_review_request_link(): void
    {
        // Purchase pending: reviewUrl() harus null
        $purchasePending = Purchase::create([
            'order_code' => 'ORD-PEND-1',
            'customer_name' => 'Rina Pembeli',
            'customer_phone' => '081234567890',
            'total_price' => 50000,
            'status' => 'pending',
        ]);
        $this->assertNull($purchasePending->reviewUrl());
        $this->assertNull(ReviewRequestLink::forPurchase($purchasePending));

        // Purchase paid: reviewUrl() menghasilkan token dan route yang valid
        $purchasePaid = Purchase::create([
            'order_code' => 'ORD-PAID-1',
            'customer_name' => 'Rina Pembeli',
            'customer_phone' => '081234567890',
            'total_price' => 50000,
            'status' => 'paid',
        ]);
        $urlPurchase = $purchasePaid->reviewUrl();
        $this->assertNotNull($urlPurchase);
        $this->assertNotEmpty($purchasePaid->fresh()->review_token);

        $waLinkPurchase = ReviewRequestLink::forPurchase($purchasePaid);
        $this->assertStringContainsString('https://wa.me/6281234567890?text=', $waLinkPurchase);
        $this->assertStringContainsString(rawurlencode($urlPurchase), $waLinkPurchase);

        // Donation received: reviewUrl() valid
        $donationReceived = Donation::create([
            'donor_name' => 'Budi Donatur',
            'donor_phone' => '085712345678',
            'donor_city' => 'Mojokerto',
            'status' => 'received',
            'item_count' => 5,
        ]);
        $urlDonation = $donationReceived->reviewUrl();
        $this->assertNotNull($urlDonation);

        $waLinkDonation = ReviewRequestLink::forDonation($donationReceived);
        $this->assertStringContainsString('https://wa.me/6285712345678?text=', $waLinkDonation);
        $this->assertStringContainsString(rawurlencode($urlDonation), $waLinkDonation);
    }

    /**
     * 9. Validasi token: Token salah ditolak atau transaksi belum selesai ditolak ("Tautan Tidak Valid").
     */
    public function test_public_review_token_validation_rejects_invalid_token_or_incomplete_transaction(): void
    {
        // 1. Token acak tidak ada di database
        $responseBogus = $this->get(route('reviews.create', ['token' => 'token-ngawur-tidak-ada']));
        $responseBogus->assertOk();
        $responseBogus->assertSee('Tautan Tidak Valid');

        // 2. Token ada tapi purchase masih pending (belum selesai)
        $purchasePending = Purchase::create([
            'order_code' => 'ORD-PEND-2',
            'customer_name' => 'Test Pending',
            'customer_phone' => '081234567890',
            'total_price' => 50000,
            'status' => 'pending',
            'review_token' => 'token-pending-transaksi-belum-selesai',
        ]);

        $responsePending = $this->get(route('reviews.create', ['token' => $purchasePending->review_token]));
        $responsePending->assertOk();
        $responsePending->assertSee('Tautan Tidak Valid');
    }

    /**
     * 10. Tautan valid menampilkan form dengan nama terisi dan peran terisi otomatis.
     */
    public function test_public_review_valid_token_displays_form_with_prefilled_name_and_auto_role(): void
    {
        // 1. Pembeli
        $purchase = Purchase::create([
            'order_code' => 'ORD-TOKEN-01',
            'customer_name' => 'Rina Wardhani',
            'customer_phone' => '081234567890',
            'total_price' => 75000,
            'status' => 'paid',
        ]);
        $purchaseUrl = $purchase->reviewUrl();

        $responseBuyer = $this->get($purchaseUrl);
        $responseBuyer->assertOk();
        $responseBuyer->assertSee('Ceritakan Pengalaman Berbelanja Anda');
        $responseBuyer->assertSee('Rina Wardhani');

        // 2. Donatur
        $donation = Donation::create([
            'donor_name' => 'Bambang Sudarmono',
            'donor_phone' => '081333444555',
            'donor_city' => 'Surabaya',
            'status' => 'received',
            'item_count' => 7,
        ]);
        $donationUrl = $donation->reviewUrl();

        $responseDonor = $this->get($donationUrl);
        $responseDonor->assertOk();
        $responseDonor->assertSee('Ceritakan Pengalaman Berdonasi Anda');
        $responseDonor->assertSee('Bambang Sudarmono');
    }

    /**
     * 11. Pengiriman ulasan: Peran terisi otomatis, is_active=false, dan transaksi hanya bisa diulas sekali.
     */
    public function test_public_review_submission_saves_as_inactive_and_prevents_duplicate_review(): void
    {
        $purchase = Purchase::create([
            'order_code' => 'ORD-SUBMIT-1',
            'customer_name' => 'Endah Wulandari',
            'customer_phone' => '081299988877',
            'total_price' => 120000,
            'status' => 'completed',
        ]);
        $token = $purchase->review_token ?: (fn () => $purchase->reviewUrl() ? $purchase->fresh()->review_token : '')();

        // Kirim ulasan pertama
        $postResponse = $this->post(route('reviews.submit', ['token' => $token]), [
            'name' => 'Endah Wulandari',
            'quote' => 'Tote bag kriya buatan Dimas sangat rapi dan motifnya cantik.',
            'agree_terms' => '1',
        ]);
        $postResponse->assertOk();
        $postResponse->assertSee('Terima kasih, ulasan Anda sudah kami terima.');

        $review = Review::where('purchase_id', $purchase->id)->firstOrFail();
        $this->assertEquals('Endah Wulandari', $review->name);
        $this->assertEquals('buyer', $review->source);
        $this->assertFalse($review->is_active);

        // Belum tampil di beranda sebelum disetujui admin
        $this->get(route('home'))->assertDontSee('Tote bag kriya buatan Dimas');

        // Buka tautan lagi setelah diulas -> langsung menampilkan "Terima kasih, ulasan Anda sudah kami terima"
        $reopenResponse = $this->get(route('reviews.create', ['token' => $token]));
        $reopenResponse->assertOk();
        $reopenResponse->assertSee('Terima kasih, ulasan Anda sudah kami terima.');

        // Mencoba POST lagi pada token yang sama -> ditolak dan tetap menampilkan pesan terima kasih sudah diterima
        $repostResponse = $this->post(route('reviews.submit', ['token' => $token]), [
            'name' => 'Endah Wulandari',
            'quote' => 'Ulasan duplikat yang seharusnya tidak disimpan dua kali.',
            'agree_terms' => '1',
        ]);
        $repostResponse->assertOk();
        $this->assertEquals(1, Review::where('purchase_id', $purchase->id)->count());

        // Admin menyetujui ulasan
        $this->actingAs($this->admin)->patch(route('admin.reviews.toggle', $review))->assertRedirect();
        $this->assertTrue($review->fresh()->is_active);

        // Tampil di beranda dengan format nama depan + inisial belakang ("Endah W.")
        $home = $this->get(route('home'));
        $home->assertOk();
        $home->assertSee('Endah W.');
        $home->assertSee('Pembeli terverifikasi');
        $home->assertSee('Tote bag kriya buatan Dimas');
    }

    /**
     * 12. Admin dapat mengelola review (toggle aktif, ubah urutan, dan hapus ulasan).
     */
    public function test_admin_can_curate_reviews_toggle_reorder_and_delete(): void
    {
        $review = Review::create([
            'name' => 'Danang Wicaksono',
            'role' => 'Pembeli',
            'quote' => 'Ulasan nyata untuk diuji admin kurasi.',
            'order' => 5,
            'is_active' => true,
        ]);

        // Toggle status menjadi nonaktif
        $this->actingAs($this->admin)->patch(route('admin.reviews.toggle', $review))
            ->assertRedirect();
        $this->assertFalse($review->fresh()->is_active);

        // Ulasan nonaktif tetap ada di tabel admin
        $adminIndex = $this->actingAs($this->admin)->get(route('admin.reviews.index'));
        $adminIndex->assertSee('Danang Wicaksono');
        $adminIndex->assertSee('Menunggu Persetujuan');

        // Update urutan
        $this->actingAs($this->admin)->patch(route('admin.reviews.order', $review), [
            'order' => 1,
        ])->assertRedirect();
        $this->assertEquals(1, $review->fresh()->order);

        // Hapus ulasan
        $this->actingAs($this->admin)->delete(route('admin.reviews.destroy', $review))
            ->assertRedirect();
        $this->assertDatabaseMissing('reviews', ['id' => $review->id]);
    }

    /**
     * 13. Beranda hanya menampilkan maksimal 6 ulasan teratas.
     */
    public function test_home_page_displays_a_maximum_of_six_active_reviews(): void
    {
        Review::query()->delete();

        // Buat 8 review aktif dengan urutan 1..8
        for ($i = 1; $i <= 8; $i++) {
            Review::create([
                'name' => "Pengulas Unik Ke{$i}",
                'role' => 'Pembeli Kriya',
                'quote' => "Kutipan ulasan unik dari orang ke-{$i} untuk pengujian batas enam.",
                'order' => $i,
                'is_active' => true,
            ]);
        }

        $response = $this->get(route('home'));
        $response->assertOk();

        // 6 teratas harus terlihat
        $response->assertSee('Kutipan ulasan unik dari orang ke-1');
        $response->assertSee('Kutipan ulasan unik dari orang ke-2');
        $response->assertSee('Kutipan ulasan unik dari orang ke-3');
        $response->assertSee('Kutipan ulasan unik dari orang ke-4');
        $response->assertSee('Kutipan ulasan unik dari orang ke-5');
        $response->assertSee('Kutipan ulasan unik dari orang ke-6');

        // Ulasan ke-7 dan ke-8 TIDAK boleh ada di beranda
        $response->assertDontSee('Kutipan ulasan unik dari orang ke-7');
        $response->assertDontSee('Kutipan ulasan unik dari orang ke-8');
    }
}
