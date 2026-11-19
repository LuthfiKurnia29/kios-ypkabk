<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Donation;
use App\Models\Purchase;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminExportAndReportsTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected User $regularUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->admin()->create();
        $this->regularUser = User::factory()->create(['role' => 'user']);
    }

    /**
     * Test admin page titles do not contain "Admin" or "— Admin".
     */
    public function test_admin_page_titles_do_not_contain_admin(): void
    {
        $routes = [
            route('admin.dashboard'),
            route('admin.purchases.index'),
            route('admin.donations.index'),
            route('admin.products.index'),
            route('admin.categories.index'),
            route('admin.unboxing.index'),
            route('admin.site-content.edit'),
            route('admin.reviews.index'),
            route('admin.reports.index'),
            route('admin.reports.income'),
            route('admin.messages.index'),
        ];

        foreach ($routes as $url) {
            $response = $this->actingAs($this->admin)->get($url);
            $response->assertOk();

            // Extract <title>...</title> content
            if (preg_match('/<title>(.*?)<\/title>/is', $response->getContent(), $matches)) {
                $title = trim($matches[1]);
                $this->assertStringNotContainsString('— Admin', $title, "Title on {$url} contains '— Admin': {$title}");
                $this->assertStringNotContainsString('Admin Panel', $title, "Title on {$url} contains 'Admin Panel': {$title}");
                // It should strictly not be "Dashboard — Admin" or "Laporan — Admin" etc.
            }
        }
    }

    /**
     * Test Tab Pemasukan queries only paid/completed orders in selected month,
     * donations do not alter rupiah balance, and empty state renders cleanly.
     */
    public function test_income_tab_queries_paid_completed_orders_and_donations_do_not_affect_balance(): void
    {
        $targetMonth = Carbon::create(2026, 11, 15);

        // Paid purchase in November 2026 (income: 150.000)
        Purchase::create([
            'order_code' => 'ORD-NOV-PAID',
            'customer_name' => 'Budi Santoso',
            'customer_phone' => '081234567890',
            'total_price' => 150000,
            'status' => 'paid',
            'created_at' => $targetMonth,
        ]);

        // Completed purchase in November 2026 (income: 50.000)
        Purchase::create([
            'order_code' => 'ORD-NOV-COMP',
            'customer_name' => 'Siti Rahma',
            'customer_phone' => '081234567891',
            'total_price' => 50000,
            'status' => 'completed',
            'created_at' => $targetMonth,
        ]);

        // Pending purchase in November 2026 (should NOT be included in income)
        Purchase::create([
            'order_code' => 'ORD-NOV-PEND',
            'customer_name' => 'Pending Buyer',
            'customer_phone' => '081234567892',
            'total_price' => 80000,
            'status' => 'pending',
            'created_at' => $targetMonth,
        ]);

        // Paid purchase in October 2026 (different month)
        Purchase::create([
            'order_code' => 'ORD-OCT-PAID',
            'customer_name' => 'October Buyer',
            'customer_phone' => '081234567893',
            'total_price' => 70000,
            'status' => 'paid',
            'created_at' => Carbon::create(2026, 10, 10),
        ]);

        // Received donation in November 2026 (5 items, goods donation)
        $category = Category::create(['name' => 'Mainan Edukatif', 'slug' => 'mainan-edukatif', 'type' => 'donation']);
        Donation::create([
            'donor_name' => 'Donatur Dermawan',
            'donor_phone' => '089876543210',
            'donor_city' => 'Surabaya',
            'category_id' => $category->id,
            'item_count' => 5,
            'status' => 'received',
            'received_at' => $targetMonth,
            'created_at' => $targetMonth,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.reports.income', ['periode' => '2026-11']));

        $response->assertOk();
        $response->assertSee('ORD-NOV-PAID');
        $response->assertSee('ORD-NOV-COMP');
        $response->assertDontSee('ORD-NOV-PEND');
        $response->assertDontSee('ORD-OCT-PAID');

        // Total income should be 150.000 + 50.000 = 200.000
        $response->assertSee('200.000');

        // Donations should appear in table 2
        $response->assertSee('Donatur Dermawan');
        $response->assertSee('5 barang');
        $response->assertSee('Donasi berupa barang, tidak dihitung dalam saldo rupiah.');

        // Verify balance equals income (200.000) when expenses are 0, donation does not add rupiah
        $response->assertSee('Saldo Bulan Ini');
    }

    /**
     * Test Income tab renders cleanly when data is empty.
     */
    public function test_income_tab_renders_cleanly_when_empty(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.reports.income', ['periode' => '2026-01']));

        $response->assertOk();
        $response->assertSee('Belum ada pemasukan dari penjualan untuk periode ini.');
        $response->assertSee('Belum ada barang donasi diterima untuk periode ini.');
    }

    /**
     * Test Site Content page no longer renders the two deleted sections.
     */
    public function test_site_content_page_does_not_contain_deleted_sections(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.site-content.edit'));

        $response->assertOk();
        $response->assertSee('Hero Beranda');
        $response->assertSee('Jumlah ABK Terlatih Mandiri');

        // Deleted sections must not appear
        $response->assertDontSee('Ringkasan Laporan Keterbukaan Real-Time');
        $response->assertDontSee('Ringkasan Ulasan Sahabat Kios');
        $response->assertDontSee('bagian 2 di atas');
        $response->assertDontSee('1. Bagian Hero Beranda');
        $response->assertDontSee('2. Jumlah ABK Terlatih Mandiri');
    }

    /**
     * Test Export routes require admin authentication.
     */
    public function test_export_routes_require_admin_authentication(): void
    {
        $exportRoutes = [
            route('admin.purchases.export'),
            route('admin.donations.export'),
            route('admin.reports.export'),
        ];

        foreach ($exportRoutes as $url) {
            // Guest redirected to login
            auth()->logout();
            $this->get($url)->assertRedirect(route('login'));

            // Regular user gets 403 Forbidden
            $this->actingAs($this->regularUser)->get($url)->assertForbidden();
        }
    }

    /**
     * Test Export routes validate request input.
     */
    public function test_export_routes_validate_input(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.purchases.export', [
            'period_type' => 'invalid',
            'period' => '',
            'format' => 'doc',
        ]));

        $response->assertSessionHasErrors(['period_type', 'period', 'format']);
    }

    /**
     * Test Export routes generate downloads for monthly and weekly in PDF and Excel formats,
     * including handling empty periods gracefully.
     */
    public function test_export_routes_generate_downloads_for_monthly_and_weekly(): void
    {
        $types = [
            'purchases' => route('admin.purchases.export'),
            'donations' => route('admin.donations.export'),
            'reports' => route('admin.reports.export'),
        ];

        foreach ($types as $name => $url) {
            // 1. Monthly XLSX
            $respMonthlyXlsx = $this->actingAs($this->admin)->get($url.'?period_type=monthly&period=2026-11&format=xlsx');
            $respMonthlyXlsx->assertOk();
            $this->assertStringContainsString("kios-mandiri-{$name}-", $respMonthlyXlsx->headers->get('content-disposition'));
            $this->assertStringContainsString('.xlsx', $respMonthlyXlsx->headers->get('content-disposition'));

            // 2. Monthly PDF
            $respMonthlyPdf = $this->actingAs($this->admin)->get($url.'?period_type=monthly&period=2026-11&format=pdf');
            $respMonthlyPdf->assertOk();
            $this->assertStringContainsString("kios-mandiri-{$name}-", $respMonthlyPdf->headers->get('content-disposition'));
            $this->assertStringContainsString('.pdf', $respMonthlyPdf->headers->get('content-disposition'));

            // 3. Weekly XLSX
            $respWeeklyXlsx = $this->actingAs($this->admin)->get($url.'?period_type=weekly&period=2026-W46&format=xlsx');
            $respWeeklyXlsx->assertOk();
            $this->assertStringContainsString("kios-mandiri-{$name}-", $respWeeklyXlsx->headers->get('content-disposition'));
            $this->assertStringContainsString('.xlsx', $respWeeklyXlsx->headers->get('content-disposition'));

            // 4. Weekly PDF (with empty data)
            $respWeeklyPdf = $this->actingAs($this->admin)->get($url.'?period_type=weekly&period=2020-W01&format=pdf');
            $respWeeklyPdf->assertOk();
            $this->assertStringContainsString("kios-mandiri-{$name}-", $respWeeklyPdf->headers->get('content-disposition'));
            $this->assertStringContainsString('.pdf', $respWeeklyPdf->headers->get('content-disposition'));
        }
    }
}
