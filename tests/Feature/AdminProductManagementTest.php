<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class AdminProductManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->admin()->create();
    }

    public function test_admin_can_view_product_management_page(): void
    {
        $category = Category::create([
            'name' => 'Karya ABK',
            'slug' => 'karya-abk',
            'type' => 'product',
        ]);

        Product::create([
            'category_id' => $category->id,
            'name' => 'Tote Bag Istimewa',
            'price' => 45000,
            'stock' => 10,
            'status' => 'ready',
            'isEmpty' => false,
            'source' => 'karya',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.products.index'));

        $response->assertOk();
        $response->assertSee('Manajemen Produk Katalog');
        $response->assertSee('Tote Bag Istimewa');
        $response->assertSee('Rp 45.000');
        $response->assertSee('Tambah Produk');
    }

    public function test_admin_can_store_product_with_uploaded_image_as_blob(): void
    {
        $file = UploadedFile::fake()->image('produk-rajut.jpg', 300, 300);

        $response = $this->actingAs($this->admin)->post(route('admin.products.store'), [
            'name' => 'Boneka Rajut Kelinci',
            'price' => 50000,
            'stock' => 5,
            'source' => 'karya',
            'condition' => 'baru',
            'image' => $file,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $product = Product::where('name', 'Boneka Rajut Kelinci')->first();
        $this->assertNotNull($product);
        $this->assertNotNull($product->image);
        $this->assertStringStartsWith('data:image/', $product->image);

        // Verify it displays in admin and catalog
        $adminView = $this->actingAs($this->admin)->get(route('admin.products.index'));
        $adminView->assertSee('Boneka Rajut Kelinci');
        $adminView->assertSee('data:image/');
    }

    public function test_admin_can_update_product_without_overwriting_existing_image(): void
    {
        $initialBlob = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=';

        $product = Product::create([
            'name' => 'Produk Awal',
            'price' => 20000,
            'stock' => 3,
            'status' => 'minimal_stock',
            'isEmpty' => false,
            'image' => $initialBlob,
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.products.update', $product), [
            'name' => 'Produk Awal Diperbarui',
            'price' => 25000,
            'stock' => 8,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $product->refresh();
        $this->assertEquals('Produk Awal Diperbarui', $product->name);
        $this->assertEquals(25000, (int) $product->price);
        $this->assertEquals(8, $product->stock);
        // Image must remain unchanged
        $this->assertEquals($initialBlob, $product->image);
    }

    public function test_admin_can_delete_product(): void
    {
        $product = Product::create([
            'name' => 'Produk Hapus',
            'price' => 15000,
            'stock' => 1,
            'status' => 'ready',
            'isEmpty' => false,
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.products.destroy', $product));

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }
}

