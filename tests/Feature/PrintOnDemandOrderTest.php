<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PrintOnDemandOrderTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->admin()->create();

        $category = Category::create([
            'name' => 'Karya ABK',
            'slug' => 'karya-abk',
            'is_active' => true,
        ]);

        $this->product = Product::create([
            'name' => 'Tote Bag Kanvas Lukis',
            'category_id' => $category->id,
            'price' => 45000,
            'stock' => 10,
            'status' => 'ready',
            'isEmpty' => false,
            'is_on_demand' => true,
        ]);
    }

    public function test_user_can_submit_print_on_demand_order_with_design_file_and_quantity(): void
    {
        Storage::fake('public');

        $designFile = UploadedFile::fake()->image('custom_design.png', 800, 800);

        $payload = [
            'customer_name' => 'Nur Azizah',
            'customer_phone' => '081298765432',
            'city' => 'Surabaya',
            'district' => 'Wonokromo',
            'village' => 'Darmo',
            'street_address' => 'Jl. Ciliwung No. 10',
            'address' => 'Pagar putih sebelah minimarket',
            'product_id' => $this->product->id,
            'quantity' => 12,
            'design_notes' => 'Cetak sablon di bagian depan ukuran A4, warna kain broken white.',
            'design_file' => $designFile,
            'payment_method' => 'qris',
        ];

        $response = $this->post(route('purchases.on-demand.store'), $payload);

        $response->assertSessionHasNoErrors();

        /** @var Purchase $purchase */
        $purchase = Purchase::where('order_type', 'on_demand')->first();
        $this->assertNotNull($purchase);

        $response->assertRedirect(route('purchases.show', $purchase->order_code));

        $this->assertEquals('Nur Azizah', $purchase->customer_name);
        $this->assertEquals('on_demand', $purchase->order_type);
        $this->assertEquals('waiting_approval', $purchase->status);
        $this->assertEquals(540000, (float) $purchase->total_price); // 12 * 45000
        $this->assertNotNull($purchase->design_file_path);
        Storage::disk('public')->assertExists($purchase->design_file_path);

        // Product physical inventory should not be deducted for on-demand
        $this->assertEquals(10, $this->product->fresh()->stock);

        // Verify detail item
        $detail = $purchase->detailPurchases()->first();
        $this->assertNotNull($detail);
        $this->assertEquals(12, $detail->quantity);
    }

    public function test_on_demand_order_requires_design_file_and_quantity(): void
    {
        $payload = [
            'customer_name' => 'Nur Azizah',
            'customer_phone' => '081298765432',
            'city' => 'Surabaya',
            'quantity' => 0, // Invalid min
        ];

        $response = $this->post(route('purchases.on-demand.store'), $payload);

        $response->assertSessionHasErrors(['design_file', 'quantity']);
    }

    public function test_public_order_page_displays_waiting_approval_and_hides_payment_info(): void
    {
        $purchase = Purchase::create([
            'order_code' => 'POD-20261121-TEST1',
            'order_type' => 'on_demand',
            'customer_name' => 'Budi Santoso',
            'customer_phone' => '081122334455',
            'total_price' => 150000,
            'status' => 'waiting_approval',
            'design_notes' => 'Sablon warna biru navy',
        ]);

        $response = $this->get(route('purchases.show', $purchase->order_code));

        $response->assertOk();
        $response->assertSee('Menunggu Persetujuan Admin');
        $response->assertSee('Pesanan Print on Demand Sedang Ditinjau Admin');
        $response->assertSee('Sablon warna biru navy');

        // Should NOT show payment upload form or QRIS image while waiting approval
        $response->assertDontSee('Unggah Bukti Pembayaran');
        $response->assertDontSee('api.qrserver.com');
    }

    public function test_admin_can_approve_on_demand_order(): void
    {
        $purchase = Purchase::create([
            'order_code' => 'POD-20261121-TEST2',
            'order_type' => 'on_demand',
            'customer_name' => 'Dewi Sartika',
            'customer_phone' => '081233445566',
            'total_price' => 200000,
            'status' => 'waiting_approval',
            'design_notes' => 'Kaos hitam ukuran L',
        ]);

        $this->actingAs($this->admin);

        $response = $this->patch(route('admin.purchases.approve-on-demand', $purchase), [
            'adjusted_price' => 220000,
            'admin_notes' => 'Desain disetujui. Siap diproduksi dalam 2 hari kerja.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $purchase->refresh();
        $this->assertEquals('pending', $purchase->status);
        $this->assertEquals(220000, (float) $purchase->total_price);
        $this->assertEquals('Desain disetujui. Siap diproduksi dalam 2 hari kerja.', $purchase->admin_notes);

        // Now public order page should show payment instructions
        $publicResponse = $this->get(route('purchases.show', $purchase->order_code));
        $publicResponse->assertOk();
        $publicResponse->assertSee('Pesanan Print on Demand Telah Disetujui Admin');
        $publicResponse->assertSee('Instruksi Pembayaran');
    }

    public function test_admin_can_reject_on_demand_order_with_reason(): void
    {
        $purchase = Purchase::create([
            'order_code' => 'POD-20261121-TEST3',
            'order_type' => 'on_demand',
            'customer_name' => 'Fajar Pratama',
            'customer_phone' => '081244556677',
            'total_price' => 90000,
            'status' => 'waiting_approval',
        ]);

        $this->actingAs($this->admin);

        $response = $this->patch(route('admin.purchases.reject-on-demand', $purchase), [
            'reason' => 'Resolusi gambar terlalu kecil, mohon kirimkan ulang file minimal 300 DPI.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $purchase->refresh();
        $this->assertEquals('cancelled', $purchase->status);
        $this->assertStringContainsString('Resolusi gambar terlalu kecil', $purchase->admin_notes);

        // Public order page shows rejection note
        $publicResponse = $this->get(route('purchases.show', $purchase->order_code));
        $publicResponse->assertOk();
        $publicResponse->assertSee('Pesanan Belum Dapat Disetujui / Dibatalkan');
        $publicResponse->assertSee('Resolusi gambar terlalu kecil');
    }

    public function test_admin_can_filter_on_demand_orders_in_dashboard(): void
    {
        Purchase::create([
            'order_code' => 'ORD-REGULAR-1',
            'order_type' => 'regular',
            'customer_name' => 'User Regular',
            'customer_phone' => '0811111111',
            'total_price' => 50000,
            'status' => 'pending',
        ]);

        Purchase::create([
            'order_code' => 'POD-ONDEMAND-1',
            'order_type' => 'on_demand',
            'customer_name' => 'User On Demand',
            'customer_phone' => '0822222222',
            'total_price' => 150000,
            'status' => 'waiting_approval',
        ]);

        $this->actingAs($this->admin);

        // Filter on_demand
        $response = $this->get(route('admin.purchases.index', ['order_type' => 'on_demand']));
        $response->assertOk();
        $response->assertSee('POD-ONDEMAND-1');
        $response->assertDontSee('ORD-REGULAR-1');

        // Filter regular
        $responseRegular = $this->get(route('admin.purchases.index', ['order_type' => 'regular']));
        $responseRegular->assertOk();
        $responseRegular->assertSee('ORD-REGULAR-1');
        $responseRegular->assertDontSee('POD-ONDEMAND-1');

        // Filter waiting_approval
        $responseWaiting = $this->get(route('admin.purchases.index', ['status' => 'waiting_approval']));
        $responseWaiting->assertOk();
        $responseWaiting->assertSee('POD-ONDEMAND-1');
        $responseWaiting->assertDontSee('ORD-REGULAR-1');
    }
}
