<?php

namespace Tests\Feature;

use App\Http\Services\ProductService;
use App\Http\Services\PurchaseService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class GuestQrisCheckoutTest extends TestCase
{
    use RefreshDatabase;

    protected ProductService $productService;

    protected PurchaseService $purchaseService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->productService = app(ProductService::class);
        $this->purchaseService = app(PurchaseService::class);
    }

    public function test_guest_can_checkout_without_login_and_receive_qris_payload(): void
    {
        $product = $this->productService->createDataProduct([
            'name' => 'Boneka Rajut Kelinci',
            'price' => 65000,
            'stock' => 10,
        ]);

        $response = $this->postJson(route('purchases.store'), [
            'customer_name' => 'Siti Rahma',
            'customer_phone' => '081298765432',
            'customer_address' => 'Sidoarjo',
            'notes' => 'Tolong bungkus rapi',
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 2,
                    'price' => 65000,
                ],
            ],
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'message',
            'data' => [
                'id',
                'order_code',
                'customer_name',
                'total_price',
                'status',
            ],
            'qris' => [
                'payload',
                'total_price',
                'formatted_price',
                'upload_proof_url',
            ],
        ]);

        $totalPrice = 130000;
        $this->assertEquals($totalPrice, $response->json('qris.total_price'));
        $this->assertStringContainsString('5406130000', $response->json('qris.payload'));

        $this->assertDatabaseHas('purchases', [
            'customer_name' => 'Siti Rahma',
            'customer_phone' => '081298765432',
            'total_price' => $totalPrice,
            'user_id' => null, // Guest without login
            'status' => 'pending',
        ]);
    }

    public function test_generate_dynamic_qris_creates_valid_tag_and_crc(): void
    {
        $amount = 45000;
        $qrisPayload = $this->purchaseService->generateDynamicQris($amount);

        // Tag 01 should be dynamic: 010212
        $this->assertStringContainsString('010212', $qrisPayload);
        // Tag 54 should contain 45000: 540545000
        $this->assertStringContainsString('540545000', $qrisPayload);
        // Tag 63 should be present with 4 hex checksum characters
        $this->assertMatchesRegularExpression('/6304[A-F0-9]{4}$/', $qrisPayload);
    }

    public function test_guest_can_upload_payment_proof(): void
    {
        Storage::fake('public');

        $product = $this->productService->createDataProduct([
            'name' => 'Tote Bag Lukis',
            'price' => 50000,
            'stock' => 5,
        ]);

        $purchase = $this->purchaseService->createPurchase([
            'customer_name' => 'Budi Santoso',
            'customer_phone' => '081234567890',
        ], [
            [
                'product_id' => $product->id,
                'quantity' => 1,
            ],
        ]);

        $file = UploadedFile::fake()->image('struk-transfer.jpg', 600, 800);

        $response = $this->postJson(route('purchases.upload-proof', $purchase->order_code), [
            'payment_proof' => $file,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Bukti pembayaran berhasil diunggah.',
        ]);

        $purchase->refresh();
        $this->assertNotNull($purchase->payment_proof);
        $this->assertEquals('qris', $purchase->payment_method);
        Storage::disk('public')->assertExists($purchase->payment_proof);

        $this->assertDatabaseHas('log_purchases', [
            'purchase_id' => $purchase->id,
            'action' => 'payment_proof_uploaded',
        ]);
    }

    public function test_upload_payment_proof_rejects_non_image(): void
    {
        Storage::fake('public');

        $product = $this->productService->createDataProduct([
            'name' => 'Tote Bag Lukis',
            'price' => 50000,
            'stock' => 5,
        ]);

        $purchase = $this->purchaseService->createPurchase([
            'customer_name' => 'Budi',
            'customer_phone' => '081234567890',
        ], [
            [
                'product_id' => $product->id,
                'quantity' => 1,
            ],
        ]);

        $pdfFile = UploadedFile::fake()->create('document.pdf', 100);

        $response = $this->postJson(route('purchases.upload-proof', $purchase->order_code), [
            'payment_proof' => $pdfFile,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['payment_proof']);
    }
}
