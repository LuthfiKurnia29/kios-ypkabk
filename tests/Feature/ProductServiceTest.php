<?php

namespace Tests\Feature;

use App\Http\Services\ProductService;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductServiceTest extends TestCase
{
    use RefreshDatabase;

    protected ProductService $productService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->productService = app(ProductService::class);
    }

    public function test_create_data_product_computes_status_and_is_empty(): void
    {
        $readyProduct = $this->productService->createDataProduct([
            'name' => 'Produk Ready',
            'price' => 50000,
            'stock' => 10,
        ]);
        $this->assertEquals('ready', $readyProduct->status);
        $this->assertFalse($readyProduct->isEmpty);

        $minimalProduct = $this->productService->createDataProduct([
            'name' => 'Produk Menipis',
            'price' => 25000,
            'stock' => 2,
        ]);
        $this->assertEquals('minimal_stock', $minimalProduct->status);
        $this->assertFalse($minimalProduct->isEmpty);

        $emptyProduct = $this->productService->createDataProduct([
            'name' => 'Produk Habis',
            'price' => 15000,
            'stock' => 0,
        ]);
        $this->assertEquals('empty', $emptyProduct->status);
        $this->assertTrue($emptyProduct->isEmpty);
    }

    public function test_get_products_filters_by_category_source_condition_and_search(): void
    {
        $karyaCat = Category::create(['name' => 'Karya ABK', 'slug' => 'karya-abk', 'type' => 'product']);
        $pakaianCat = Category::create(['name' => 'Pakaian Preloved', 'slug' => 'pakaian', 'type' => 'product']);

        $product1 = $this->productService->createDataProduct([
            'name' => 'Totebag Lukis Melati',
            'category_id' => $karyaCat->id,
            'price' => 45000,
            'stock' => 5,
            'source' => 'karya',
            'condition' => 'baru',
            'artisan_name' => 'Rian ABK',
        ]);

        $product2 = $this->productService->createDataProduct([
            'name' => 'Kemeja Katun Anak',
            'category_id' => $pakaianCat->id,
            'price' => 25000,
            'stock' => 1,
            'source' => 'preloved',
            'condition' => 'mulus',
        ]);

        // Filter by search
        $searchResult = $this->productService->getProducts(['search' => 'Lukis']);
        $this->assertCount(1, $searchResult);
        $this->assertEquals($product1->id, $searchResult->first()->id);

        // Filter by category slug
        $catResult = $this->productService->getProducts(['category_slug' => 'pakaian']);
        $this->assertCount(1, $catResult);
        $this->assertEquals($product2->id, $catResult->first()->id);

        // Filter by price range
        $under30Result = $this->productService->getProducts(['price_range' => 'under-30']);
        $this->assertCount(1, $under30Result);
        $this->assertEquals($product2->id, $under30Result->first()->id);

        // Filter by condition
        $conditionResult = $this->productService->getProducts(['condition' => 'baru']);
        $this->assertCount(1, $conditionResult);
        $this->assertEquals($product1->id, $conditionResult->first()->id);
    }

    public function test_update_data_product_and_update_stock(): void
    {
        $product = $this->productService->createDataProduct([
            'name' => 'Kaos Vokasi',
            'price' => 40000,
            'stock' => 5,
        ]);

        $updated = $this->productService->updateDataProduct($product->id, [
            'name' => 'Kaos Vokasi Premium',
            'price' => 45000,
        ]);
        $this->assertEquals('Kaos Vokasi Premium', $updated->name);
        $this->assertEquals(45000, (int) $updated->price);

        // Deduct stock to 1
        $stockUpdated = $this->productService->updateStock($product->id, -4);
        $this->assertEquals(1, $stockUpdated->stock);
        $this->assertEquals('minimal_stock', $stockUpdated->status);

        // Deduct stock to 0
        $stockEmpty = $this->productService->updateStock($product->id, -1);
        $this->assertEquals(0, $stockEmpty->stock);
        $this->assertEquals('empty', $stockEmpty->status);
        $this->assertTrue($stockEmpty->isEmpty);
    }

    public function test_get_featured_and_available_products(): void
    {
        $this->productService->createDataProduct([
            'name' => 'Produk Unggulan',
            'price' => 30000,
            'stock' => 5,
            'is_featured' => true,
        ]);

        $this->productService->createDataProduct([
            'name' => 'Produk Biasa Habis',
            'price' => 20000,
            'stock' => 0,
            'is_featured' => true,
        ]);

        $featured = $this->productService->getFeaturedProducts();
        $this->assertCount(1, $featured);
        $this->assertEquals('Produk Unggulan', $featured->first()->name);

        $available = $this->productService->getAvailableProducts();
        $this->assertCount(1, $available);
    }

    public function test_delete_data_product(): void
    {
        $product = $this->productService->createDataProduct([
            'name' => 'Produk Hapus',
            'price' => 10000,
            'stock' => 2,
        ]);

        $deleted = $this->productService->deleteDataProduct($product);
        $this->assertTrue($deleted);
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }
}
