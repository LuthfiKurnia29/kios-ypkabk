<?php

namespace Tests\Feature;

use App\Models\Donation;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminNavigationTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->admin()->create();
    }

    public function test_admin_dashboard_renders_navigation_links(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertSee(route('admin.dashboard'));
        $response->assertSee(route('admin.purchases.index'));
        $response->assertSee(route('admin.donations.index'));
        $response->assertSee(route('katalog'));
        $response->assertSee(route('home'));
        $response->assertSee('Dashboard');
        $response->assertSee('Pesanan');
        $response->assertSee('Transaksi');
        $response->assertSee('Donasi Masuk');
        $response->assertSee('Logout Keluar');
    }

    public function test_admin_purchases_page_renders_cleanly_with_active_state(): void
    {
        Purchase::create([
            'order_code' => 'ORD-TEST-NAV-1',
            'customer_name' => 'Budi Navigasi',
            'customer_phone' => '081234567890',
            'customer_address' => 'Surabaya',
            'total_price' => 50000,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.purchases.index'));

        $response->assertOk();
        $response->assertSee('ORD-TEST-NAV-1');
        $response->assertSee('Budi Navigasi');
        $response->assertSee('Daftar Pesanan & Transaksi');
    }

    public function test_admin_donations_page_renders_cleanly_with_active_state(): void
    {
        Donation::create([
            'donor_name' => 'Donatur Dermawan',
            'donor_phone' => '08987654321',
            'donor_city' => 'Malang',
            'category_name' => 'Buku Anak',
            'description' => '1 kardus buku ensiklopedia',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.donations.index'));

        $response->assertOk();
        $response->assertSee('Donatur Dermawan');
        $response->assertSee('Daftar Donasi Preloved Masuk');
    }
}
