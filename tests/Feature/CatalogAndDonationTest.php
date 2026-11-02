<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\DetailPurchase;
use App\Models\Donation;
use App\Models\Product;
use App\Models\Purchase;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogAndDonationTest extends TestCase
{
    use RefreshDatabase;

    public function test_database_seeder_populates_catalog_and_meta_data(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->assertDatabaseHas('categories', ['slug' => 'karya-abk']);
        $this->assertDatabaseHas('products', ['name' => 'Tote Bag Kanvas Lukis Pelangi Harapan']);
        $this->assertDatabaseHas('reviews', ['name' => 'Nanie Kurniawati']);
        $this->assertDatabaseHas('impact_metrics', ['label' => 'Barang Preloved Tersalurkan']);

        $category = Category::where('slug', 'karya-abk')->first();
        $this->assertNotNull($category);
        $this->assertTrue($category->products()->count() > 0);

        $product = Product::where('name', 'Tote Bag Kanvas Lukis Pelangi Harapan')->first();
        $this->assertNotNull($product);
        $this->assertEquals($category->id, $product->category->id);
    }

    public function test_donation_can_be_stored(): void
    {
        $category = Category::create([
            'name' => 'Pakaian Anak Preloved',
            'slug' => 'pakaian-anak-preloved',
            'type' => 'donation',
        ]);

        $donation = Donation::create([
            'donor_name' => 'Budi Santoso',
            'donor_phone' => '08123456789',
            'donor_city' => 'Sidoarjo',
            'category_id' => $category->id,
            'category_name' => $category->name,
            'description' => '2 kardus pakaian anak usia 4-7 tahun',
            'status' => 'pending',
        ]);

        $this->assertDatabaseHas('donations', [
            'donor_name' => 'Budi Santoso',
            'donor_city' => 'Sidoarjo',
            'status' => 'pending',
        ]);

        $this->assertEquals($category->id, $donation->category->id);
    }

    public function test_guest_purchase_can_be_created_without_registered_user(): void
    {
        $product = Product::create([
            'name' => 'Boneka Rajut ABK',
            'stock' => 5,
            'status' => 'ready',
            'isEmpty' => false,
            'price' => 35000,
            'source' => 'karya',
            'condition' => 'baru',
        ]);

        $purchase = Purchase::create([
            'user_id' => null,
            'order_code' => 'ORD-TEST-001',
            'customer_name' => 'Siti Fatimah',
            'customer_phone' => '08987654321',
            'customer_address' => 'Jl. Pahlawan No. 10, Mojokerto',
            'total_price' => 35000,
            'status' => 'pending',
        ]);

        $detail = DetailPurchase::create([
            'purchase_id' => $purchase->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => 35000,
            'total_price' => 35000,
        ]);

        $this->assertDatabaseHas('purchases', [
            'order_code' => 'ORD-TEST-001',
            'user_id' => null,
            'customer_name' => 'Siti Fatimah',
        ]);

        $this->assertEquals(1, $purchase->detailPurchases()->count());
        $this->assertEquals($product->id, $purchase->detailPurchases()->first()->product->id);
    }
}
