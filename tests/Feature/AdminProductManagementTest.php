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
        $response->assertSee('Produk');
        $response->assertSee('Tote Bag Istimewa');
        $response->assertSee('Rp 45.000');
        $response->assertSee('Tambah Produk');
    }

    public function test_admin_can_store_product_with_uploaded_image_as_blob(): void
    {
        $file = UploadedFile::fake()->create('produk-rajut.jpg', 300, 'image/jpeg');

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

    public function test_admin_can_store_product_with_multiple_images(): void
    {
        $file1 = UploadedFile::fake()->create('foto-1.jpg', 200, 'image/jpeg');
        $file2 = UploadedFile::fake()->create('foto-2.png', 200, 'image/png');

        $response = $this->actingAs($this->admin)->post(route('admin.products.store'), [
            'name' => 'Produk Multi Foto',
            'price' => 75000,
            'stock' => 4,
            'source' => 'karya',
            'condition' => 'baru',
            'images' => [$file1, $file2],
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $product = Product::with('images')->where('name', 'Produk Multi Foto')->first();
        $this->assertNotNull($product);
        $this->assertCount(2, $product->images);
        $this->assertNotNull($product->image);
        $this->assertEquals($product->images->first()->image, $product->image);
        $this->assertDatabaseHas('product_images', ['product_id' => $product->id]);
    }

    public function test_admin_can_delete_specific_product_image(): void
    {
        $product = Product::create([
            'name' => 'Produk Hapus Foto',
            'price' => 30000,
            'stock' => 2,
            'status' => 'ready',
            'isEmpty' => false,
            'image' => 'data:image/jpeg;base64,sample1',
        ]);

        $image1 = $product->images()->create(['image' => 'data:image/jpeg;base64,sample1']);
        $image2 = $product->images()->create(['image' => 'data:image/jpeg;base64,sample2']);

        $response = $this->actingAs($this->admin)
            ->delete(route('admin.products.images.destroy', [$product, $image1]));

        $response->assertRedirect();
        $this->assertDatabaseMissing('product_images', ['id' => $image1->id]);
        $this->assertDatabaseHas('product_images', ['id' => $image2->id]);

        $product->refresh();
        $this->assertEquals('data:image/jpeg;base64,sample2', $product->image);
    }

    public function test_admin_can_delete_cover_image(): void
    {
        $product = Product::create([
            'name' => 'Produk Hapus Sampul',
            'price' => 20000,
            'stock' => 1,
            'status' => 'ready',
            'isEmpty' => false,
            'image' => 'data:image/jpeg;base64,coversample',
        ]);

        $response = $this->actingAs($this->admin)
            ->delete(route('admin.products.cover.destroy', $product));

        $response->assertRedirect();
        $product->refresh();
        $this->assertNull($product->image);
    }

    public function test_user_can_view_product_detail_page_with_multiple_images(): void
    {
        $product = Product::create([
            'name' => 'Gantungan Kunci Manik Unik',
            'price' => 15000,
            'stock' => 5,
            'status' => 'ready',
            'isEmpty' => false,
            'description' => 'Gantungan kunci manik-manik buatan anak istimewa.',
            'source' => 'karya',
            'artisan_name' => 'Dimas',
        ]);

        $product->images()->create(['image' => 'data:image/jpeg;base64,unik1']);
        $product->images()->create(['image' => 'data:image/jpeg;base64,unik2']);

        $response = $this->get(route('products.show', $product));

        $response->assertOk();
        $response->assertSee('Gantungan Kunci Manik Unik');
        $response->assertSee('Rp 15.000');
        $response->assertSee('Dimas');
        $response->assertSee('data:image/jpeg;base64,unik1');
        $response->assertSee('data:image/jpeg;base64,unik2');
        $response->assertSee('Semua Foto (2)');
    }

    public function test_admin_can_view_product_detail_page(): void
    {
        $product = Product::create([
            'name' => 'Lukisan Kanvas Mentari',
            'price' => 120000,
            'stock' => 3,
            'status' => 'ready',
            'isEmpty' => false,
            'description' => 'Lukisan pemandangan indah oleh anak berkebutuhan khusus.',
            'source' => 'karya',
            'artisan_name' => 'Ahmad',
        ]);

        $product->images()->create(['image' => 'data:image/jpeg;base64,kanvas1']);

        $response = $this->actingAs($this->admin)->get(route('admin.products.show', $product));

        $response->assertOk();
        $response->assertSee('Lukisan Kanvas Mentari');
        $response->assertSee('Rp 120.000');
        $response->assertSee('Ahmad');
        $response->assertSee('Galeri Foto Produk');
        $response->assertSee('Riwayat Pesanan Produk Ini');
    }
}
