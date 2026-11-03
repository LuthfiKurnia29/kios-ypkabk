<?php

namespace Tests\Feature;

use App\Http\Services\ProductService;
use App\Http\Services\PurchaseService;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use Tests\TestCase;

class PurchaseServiceTest extends TestCase
{
    use RefreshDatabase;

    protected PurchaseService $purchaseService;

    protected ProductService $productService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->productService = app(ProductService::class);
        $this->purchaseService = app(PurchaseService::class);
    }

    public function test_create_purchase_order_deducts_stock_and_logs_audit(): void
    {
        $product = $this->productService->createDataProduct([
            'name' => 'Buku Cerita ABK',
            'price' => 30000,
            'stock' => 5,
        ]);

        $purchase = $this->purchaseService->createPurchase([
            'customer_name' => 'Rina Wijaya',
            'customer_phone' => '081234567890',
            'customer_address' => 'Jl. Mawar No. 12',
            'notes' => 'Tolong dibungkus kado',
        ], [
            [
                'product_id' => $product->id,
                'quantity' => 2,
            ],
        ]);

        $this->assertDatabaseHas('purchases', [
            'id' => $purchase->id,
            'customer_name' => 'Rina Wijaya',
            'total_price' => 60000,
            'status' => 'pending',
        ]);

        $this->assertDatabaseHas('detail_purchases', [
            'purchase_id' => $purchase->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'total_price' => 60000,
        ]);

        $this->assertDatabaseHas('log_purchases', [
            'purchase_id' => $purchase->id,
            'action' => 'created',
            'desc' => 'Pesanan baru dibuat.',
        ]);

        // Stock should be deducted from 5 to 3
        $refreshedProduct = Product::find($product->id);
        $this->assertEquals(3, $refreshedProduct->stock);
    }

    public function test_cannot_create_purchase_when_stock_insufficient(): void
    {
        $product = $this->productService->createDataProduct([
            'name' => 'Produk Langka',
            'price' => 50000,
            'stock' => 1,
        ]);

        $this->expectException(InvalidArgumentException::class);

        $this->purchaseService->createPurchase([
            'customer_name' => 'Ali',
            'customer_phone' => '0812345678',
        ], [
            [
                'product_id' => $product->id,
                'quantity' => 5,
            ],
        ]);
    }

    public function test_cancelling_purchase_restores_product_stock(): void
    {
        $product = $this->productService->createDataProduct([
            'name' => 'Tas Rajut',
            'price' => 70000,
            'stock' => 4,
        ]);

        $purchase = $this->purchaseService->createPurchase([
            'customer_name' => 'Dewi',
            'customer_phone' => '08111222333',
        ], [
            [
                'product_id' => $product->id,
                'quantity' => 2,
            ],
        ]);

        $this->assertEquals(2, Product::find($product->id)->stock);

        $this->purchaseService->updateStatus($purchase->id, 'cancelled', 'Pembeli membatalkan pesanan.');

        $this->assertEquals(4, Product::find($product->id)->stock);

        $this->assertDatabaseHas('log_purchases', [
            'purchase_id' => $purchase->id,
            'action' => 'status_changed_to_cancelled',
        ]);
    }
}
