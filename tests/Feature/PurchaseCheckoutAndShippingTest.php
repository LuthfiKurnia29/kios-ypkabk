<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Donation;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PurchaseCheckoutAndShippingTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->admin()->create();

        $category = Category::create([
            'name' => 'Kriya & Vokasi',
            'slug' => 'kriya-vokasi',
            'is_active' => true,
        ]);

        $this->product = Product::create([
            'name' => 'Dompet Perca Batik ABK',
            'category_id' => $category->id,
            'price' => 45000,
            'stock' => 15,
            'status' => 'ready',
            'isEmpty' => false,
            'image' => 'sample-dompet.jpg',
        ]);
    }

    public function test_checkout_saves_structured_address_and_payment_method_without_email(): void
    {
        $payload = [
            'customer_name' => 'Ahmad Faiz',
            'customer_phone' => '081234567890',
            'city' => 'Sidoarjo',
            'district' => 'Waru',
            'village' => 'Tropodo',
            'street_address' => 'Jl. Anggrek No. 25',
            'address' => 'RT 04 / RW 02, seberang masjid Al-Ittihad, pagar warna abu-abu',
            'payment_method' => 'bank_transfer',
            'bank_name' => 'BCA',
            'notes' => 'Tolong kirim sebelum hari Jumat',
            'items' => [
                [
                    'product_id' => $this->product->id,
                    'quantity' => 2,
                    'price' => 45000,
                ],
            ],
        ];

        $response = $this->postJson(route('purchases.store'), $payload);

        $response->assertCreated();
        $response->assertJson([
            'message' => 'Pesanan berhasil dibuat.',
            'payment_method' => 'bank_transfer',
        ]);

        $this->assertDatabaseHas('purchases', [
            'customer_name' => 'Ahmad Faiz',
            'customer_phone' => '081234567890',
            'city' => 'Sidoarjo',
            'district' => 'Waru',
            'village' => 'Tropodo',
            'street_address' => 'Jl. Anggrek No. 25',
            'address' => 'RT 04 / RW 02, seberang masjid Al-Ittihad, pagar warna abu-abu',
            'payment_method' => 'bank_transfer',
            'bank_name' => 'BCA',
            'total_price' => 90000,
            'status' => 'pending',
        ]);

        $purchase = Purchase::where('customer_name', 'Ahmad Faiz')->first();
        $this->assertNotNull($purchase);

        // International phone
        $this->assertEquals('6281234567890', $purchase->international_phone);

        // Full address contains structured parts and landmark
        $this->assertStringContainsString('Jl. Anggrek No. 25', $purchase->full_address);
        $this->assertStringContainsString('Desa/Kel. Tropodo', $purchase->full_address);
        $this->assertStringContainsString('Kec. Waru', $purchase->full_address);
        $this->assertStringContainsString('Sidoarjo', $purchase->full_address);
        $this->assertStringContainsString('Patokan: RT 04 / RW 02', $purchase->full_address);

        // WhatsApp receipt URL
        $this->assertStringContainsString('https://wa.me/6281234567890', $purchase->whatsapp_receipt_url);
        $this->assertStringContainsString($purchase->order_code, $purchase->whatsapp_receipt_url);
    }

    public function test_checkout_with_specific_bank_stores_and_displays_selected_bank(): void
    {
        $payload = [
            'customer_name' => 'Bambang Supriyanto',
            'customer_phone' => '081298765432',
            'city' => 'Surabaya',
            'district' => 'Wonokromo',
            'village' => 'Sawunggaling',
            'street_address' => 'Jl. Ciliwung No. 12',
            'address' => 'Pagar hijau',
            'payment_method' => 'bank_transfer',
            'bank_name' => 'BRI',
            'items' => [
                [
                    'product_id' => $this->product->id,
                    'quantity' => 1,
                    'price' => 45000,
                ],
            ],
        ];

        $response = $this->postJson(route('purchases.store'), $payload);
        $response->assertCreated();

        $purchase = Purchase::where('customer_name', 'Bambang Supriyanto')->first();
        $this->assertNotNull($purchase);
        $this->assertEquals('BRI', $purchase->bank_name);

        // Public page reflects BRI account details
        $publicResponse = $this->get(route('purchases.show', $purchase->order_code));
        $publicResponse->assertOk();
        $publicResponse->assertSee('Bank Rakyat Indonesia (BRI)');
        $publicResponse->assertSee('009-601-002-345-502'); // No rekening BRI
        $publicResponse->assertSee('Yayasan Peduli Kasih ABK');

        // Admin purchases page displays Transfer BRI
        $adminResponse = $this->actingAs($this->admin)->get(route('admin.purchases.index'));
        $adminResponse->assertOk();
        $adminResponse->assertSee('Transfer BRI');
    }

    public function test_public_purchase_page_displays_order_details_whatsapp_receipt_link_and_bank_info(): void
    {
        $purchase = Purchase::create([
            'order_code' => 'ORD-TEST-BANK-01',
            'customer_name' => 'Dewi Safitri',
            'customer_phone' => '085711223344',
            'city' => 'Mojokerto',
            'district' => 'Magersari',
            'village' => 'Kedundung',
            'street_address' => 'Jl. Pahlawan No. 8',
            'address' => 'Rumah tingkat dua samping minimarket',
            'payment_method' => 'bank_transfer',
            'bank_name' => 'BCA',
            'total_price' => 45000,
            'status' => 'pending',
        ]);

        $response = $this->get(route('purchases.show', $purchase->order_code));

        $response->assertOk();
        $response->assertSee('ORD-TEST-BANK-01');
        $response->assertSee('Dewi Safitri');
        $response->assertSee('Jl. Pahlawan No. 8');
        $response->assertSee('Kedundung');
        $response->assertSee('Magersari');
        $response->assertSee('Mojokerto');
        $response->assertSee('Rumah tingkat dua samping minimarket');
        $response->assertSee('018-384-9900'); // No rekening BCA
        $response->assertSee('Yayasan Peduli Kasih ABK');
        $response->assertSee('Kirim Nota ke WhatsApp Saya');
        $response->assertSee('Salin Tautan');
        $response->assertSee('Salin Nota');
        $response->assertSee('https://wa.me/6285711223344', false);
    }

    public function test_public_purchase_page_displays_qris_instruction_for_qris_payment_method(): void
    {
        $purchase = Purchase::create([
            'order_code' => 'ORD-TEST-QRIS-01',
            'customer_name' => 'Budi Santoso',
            'customer_phone' => '081233445566',
            'city' => 'Surabaya',
            'district' => 'Wonokromo',
            'village' => 'Darmo',
            'street_address' => 'Jl. Diponegoro No. 10',
            'address' => 'Pagar putih',
            'payment_method' => 'qris',
            'total_price' => 50000,
            'status' => 'pending',
        ]);

        $response = $this->get(route('purchases.show', $purchase->order_code));

        $response->assertOk();
        $response->assertSee('QRIS Kios Mandiri');
        $response->assertSee('Scan dengan Aplikasi Mobile Banking / E-Wallet');
        $response->assertDontSee('018-384-9900'); // Bukan transfer bank
    }

    public function test_admin_can_update_courier_and_tracking_number_and_status_changes_to_shipped(): void
    {
        Storage::fake('public');

        $purchase = Purchase::create([
            'order_code' => 'ORD-SHIP-01',
            'customer_name' => 'Rina Melati',
            'customer_phone' => '081987654321',
            'city' => 'Sidoarjo',
            'district' => 'Candi',
            'village' => 'Gelam',
            'street_address' => 'Jl. Candi Baru 5',
            'address' => 'Depan gapura',
            'payment_method' => 'qris',
            'total_price' => 100000,
            'status' => 'paid', // Paid status before shipping
        ]);

        $receiptFile = UploadedFile::fake()->create('resi-jne.jpg', 500, 'image/jpeg');

        $response = $this->actingAs($this->admin)->patch(route('admin.purchases.update-shipping', $purchase), [
            'courier_name' => 'JNE',
            'tracking_number' => 'JNE123456789ID',
            'shipping_receipt' => $receiptFile,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $purchase->refresh();
        $this->assertEquals('JNE', $purchase->courier_name);
        $this->assertEquals('JNE123456789ID', $purchase->tracking_number);
        $this->assertEquals('shipped', $purchase->status); // Status automatically becomes shipped
        $this->assertNotNull($purchase->shipping_receipt_path);
        Storage::disk('public')->assertExists($purchase->shipping_receipt_path);

        // Verify public tracking page reflects the shipping data
        $publicResponse = $this->get(route('purchases.show', $purchase->order_code));
        $publicResponse->assertOk();
        $publicResponse->assertSee('JNE');
        $publicResponse->assertSee('JNE123456789ID');
        $publicResponse->assertSee('Sedang Dikirim');
        $publicResponse->assertSee('Foto Bukti Resi');
    }

    public function test_admin_can_set_ambil_di_kios_without_tracking_number(): void
    {
        $purchase = Purchase::create([
            'order_code' => 'ORD-AMBIL-01',
            'customer_name' => 'Wawan Prasetyo',
            'customer_phone' => '081333444555',
            'payment_method' => 'qris',
            'total_price' => 45000,
            'status' => 'paid',
        ]);

        $response = $this->actingAs($this->admin)->patch(route('admin.purchases.update-shipping', $purchase), [
            'courier_name' => 'Ambil di Kios',
        ]);

        $response->assertRedirect();
        $purchase->refresh();
        $this->assertEquals('Ambil di Kios', $purchase->courier_name);
        $this->assertEquals('shipped', $purchase->status);
    }

    public function test_donation_supports_multi_photo_upload_up_to_5_photos(): void
    {
        Storage::fake('public');

        $photos = [
            UploadedFile::fake()->create('baju-1.jpg', 600, 'image/jpeg'),
            UploadedFile::fake()->create('baju-2.png', 700, 'image/png'),
            UploadedFile::fake()->create('baju-3.webp', 500, 'image/webp'),
        ];

        $response = $this->postJson(route('donations.store'), [
            'donor_name' => 'Maya Multi Foto',
            'donor_phone' => '081234567899',
            'donor_city' => 'Sidoarjo',
            'item_count' => 6,
            'description' => '3 pasang baju dan celana anak',
            'item_photos' => $photos,
        ]);

        $response->assertCreated();
        $response->assertJson(['success' => true]);

        $donation = Donation::where('donor_name', 'Maya Multi Foto')->first();
        $this->assertNotNull($donation);
        $this->assertIsArray($donation->item_photos);
        $this->assertCount(3, $donation->item_photos);
        $this->assertNotNull($donation->item_photo_path);

        foreach ($donation->item_photos as $path) {
            Storage::disk('public')->assertExists($path);
        }

        // Check Admin donation page shows thumbnails for all 3 photos
        $adminResponse = $this->actingAs($this->admin)->get(route('admin.donations.index'));
        $adminResponse->assertOk();
        $adminResponse->assertSee('Maya Multi Foto');
        foreach ($donation->item_photos as $path) {
            $adminResponse->assertSee($path);
        }

        // Test uploading more than 5 photos fails validation
        $tooManyPhotos = [];
        for ($i = 0; $i < 6; $i++) {
            $tooManyPhotos[] = UploadedFile::fake()->create("foto-{$i}.jpg", 200, 'image/jpeg');
        }

        $failResponse = $this->postJson(route('donations.store'), [
            'donor_name' => 'Donatur Lebih 5',
            'donor_phone' => '081234567899',
            'donor_city' => 'Surabaya',
            'item_photos' => $tooManyPhotos,
        ]);

        $failResponse->assertStatus(422);
        $failResponse->assertJsonValidationErrors(['item_photos']);
    }

    public function test_admin_pages_do_not_render_duplicate_flash_session_messages(): void
    {
        $sessionMsg = 'Aksi pengujian admin berhasil 100%';

        $routes = [
            route('admin.donations.index'),
            route('admin.purchases.index'),
            route('admin.categories.index'),
            route('admin.messages.index'),
            route('admin.reviews.index'),
            route('admin.unboxing.index'),
        ];

        foreach ($routes as $url) {
            $response = $this->actingAs($this->admin)
                ->withSession(['success' => $sessionMsg])
                ->get($url);

            $response->assertOk();
            $content = $response->getContent();

            // Message must appear exactly ONCE (not duplicated)
            $count = substr_count($content, $sessionMsg);
            $this->assertEquals(
                1,
                $count,
                "Flash session message duplicated in page: {$url} (appeared {$count} times, expected 1)"
            );
        }
    }
}
