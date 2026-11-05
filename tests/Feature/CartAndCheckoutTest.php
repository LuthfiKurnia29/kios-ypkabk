<?php

namespace Tests\Feature;

use App\Http\Services\ProductService;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartAndCheckoutTest extends TestCase
{
    use RefreshDatabase;

    protected ProductService $productService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->productService = app(ProductService::class);
    }

    public function test_keranjang_page_can_be_rendered(): void
    {
        $response = $this->get(route('keranjang'));

        $response->assertStatus(200);
        $response->assertSee('Keranjang Belanja &amp; Donasi', false);
        $response->assertSee('Keranjang Belanja Masih Kosong');
    }

    public function test_checkout_page_can_be_rendered(): void
    {
        $response = $this->get(route('checkout'));

        $response->assertStatus(200);
        $response->assertSee('Checkout &amp; Pengiriman Pesanan', false);
        $response->assertSee('Informasi Pemesan &amp; Alamat Kirim', false);
    }

    public function test_guest_can_submit_checkout_purchase_order(): void
    {
        $category = Category::create([
            'name' => 'Karya Seni ABK',
            'slug' => 'karya-abk',
            'type' => 'product',
        ]);

        $product1 = $this->productService->createDataProduct([
            'category_id' => $category->id,
            'name' => 'Tote Bag Kanvas Lukis Pelangi',
            'price' => 45000,
            'stock' => 10,
        ]);

        $product2 = $this->productService->createDataProduct([
            'category_id' => $category->id,
            'name' => 'Boneka Rajut Amigurumi',
            'price' => 65000,
            'stock' => 5,
        ]);

        $payload = [
            'customer_name' => 'Ibu Siti Rahmawati',
            'customer_phone' => '081234567890',
            'customer_address' => 'Jl. Mawar No. 15, Sidoarjo, Jawa Timur',
            'notes' => 'Tolong bungkus rapi dengan pita donasi',
            'items' => [
                [
                    'product_id' => $product1->id,
                    'quantity' => 2,
                    'price' => 45000,
                ],
                [
                    'product_id' => $product2->id,
                    'quantity' => 1,
                    'price' => 65000,
                ],
            ],
        ];

        $response = $this->postJson(route('purchases.store'), $payload);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'message',
            'data' => [
                'id',
                'order_code',
                'customer_name',
                'customer_phone',
                'total_price',
                'status',
            ],
        ]);

        $this->assertDatabaseHas('purchases', [
            'customer_name' => 'Ibu Siti Rahmawati',
            'customer_phone' => '081234567890',
            'total_price' => 155000,
            'status' => 'pending',
        ]);

        $this->assertDatabaseHas('detail_purchases', [
            'product_id' => $product1->id,
            'quantity' => 2,
            'price' => 45000,
            'total_price' => 90000,
        ]);

        $this->assertDatabaseHas('detail_purchases', [
            'product_id' => $product2->id,
            'quantity' => 1,
            'price' => 65000,
            'total_price' => 65000,
        ]);

        // Verifikasi pengurangan stok otomatis
        $this->assertEquals(8, $product1->fresh()->stock);
        $this->assertEquals(4, $product2->fresh()->stock);
    }
}
