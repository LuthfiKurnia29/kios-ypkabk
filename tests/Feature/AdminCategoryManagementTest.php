<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCategoryManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->admin()->create();
    }

    public function test_guest_and_regular_user_cannot_access_categories(): void
    {
        $guestResponse = $this->get(route('admin.categories.index'));
        $guestResponse->assertRedirect(route('login'));

        $regularUser = User::factory()->create(['role' => 'user']);
        $userResponse = $this->actingAs($regularUser)->get(route('admin.categories.index'));
        $userResponse->assertForbidden();
    }

    public function test_admin_can_view_category_management_page(): void
    {
        $category = Category::create([
            'name' => 'Kriya & Anyaman',
            'slug' => 'kriya-anyaman',
            'type' => 'product',
            'icon' => '🧺',
            'description' => 'Kerajinan anyaman bambu dan rotan',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.categories.index'));

        $response->assertOk();
        $response->assertSee('Kategori');
        $response->assertSee('Kriya & Anyaman');
        $response->assertSee('kriya-anyaman');
        $response->assertSee('🧺');
        $response->assertSee('Tambah Kategori');
    }

    public function test_admin_can_create_category_with_auto_generated_slug(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.categories.store'), [
            'name' => 'Lukisan Kanvas Mini',
            'type' => 'product',
            'icon' => '🎨',
            'description' => 'Lukisan hasil workshop anak ABK',
            'is_active' => '1',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $category = Category::where('name', 'Lukisan Kanvas Mini')->first();
        $this->assertNotNull($category);
        $this->assertEquals('lukisan-kanvas-mini', $category->slug);
        $this->assertEquals('product', $category->type);
        $this->assertEquals('🎨', $category->icon);
        $this->assertTrue($category->is_active);
    }

    public function test_admin_can_update_category(): void
    {
        $category = Category::create([
            'name' => 'Kategori Lama',
            'slug' => 'kategori-lama',
            'type' => 'product',
            'icon' => '📦',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.categories.update', $category), [
            'name' => 'Kategori Baru',
            'slug' => 'kategori-baru',
            'type' => 'product',
            'icon' => '🎁',
            'description' => 'Keterangan baru',
            'is_active' => '1',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $category->refresh();
        $this->assertEquals('Kategori Baru', $category->name);
        $this->assertEquals('kategori-baru', $category->slug);
        $this->assertEquals('🎁', $category->icon);
    }

    public function test_admin_can_delete_category(): void
    {
        $category = Category::create([
            'name' => 'Kategori Dihapus',
            'slug' => 'kategori-dihapus',
            'type' => 'product',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.categories.destroy', $category));

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    public function test_newly_created_category_appears_when_adding_and_editing_product(): void
    {
        // 1. Create a category
        $category = Category::create([
            'name' => 'Aksesoris Manik-Manik',
            'slug' => 'aksesoris-manik-manik',
            'type' => 'product',
            'icon' => '📿',
            'is_active' => true,
        ]);

        // 2. Check that it appears on the admin product page (dropdown options for Add & Edit)
        $productPageResponse = $this->actingAs($this->admin)->get(route('admin.products.index'));
        $productPageResponse->assertOk();
        $productPageResponse->assertSee('Aksesoris Manik-Manik');
        $productPageResponse->assertSee((string) $category->id);

        // 3. Store a product with this new category
        $storeResponse = $this->actingAs($this->admin)->post(route('admin.products.store'), [
            'name' => 'Gelang Pelangi',
            'price' => 15000,
            'stock' => 20,
            'category_id' => $category->id,
            'source' => 'karya',
            'condition' => 'baru',
        ]);
        $storeResponse->assertRedirect();

        $product = Product::where('name', 'Gelang Pelangi')->first();
        $this->assertNotNull($product);
        $this->assertEquals($category->id, $product->category_id);

        // 4. Update the product to another or same category
        $category2 = Category::create([
            'name' => 'Gantungan Kunci',
            'slug' => 'gantungan-kunci',
            'type' => 'product',
            'is_active' => true,
        ]);

        $updateResponse = $this->actingAs($this->admin)->put(route('admin.products.update', $product), [
            'name' => 'Gelang Pelangi Edisi Spesial',
            'price' => 18000,
            'stock' => 15,
            'category_id' => $category2->id,
        ]);
        $updateResponse->assertRedirect();

        $product->refresh();
        $this->assertEquals('Gelang Pelangi Edisi Spesial', $product->name);
        $this->assertEquals($category2->id, $product->category_id);
    }
}
