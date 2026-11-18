<?php

namespace Tests\Feature;

use App\Http\Services\FinancialReportService;
use App\Models\Expense;
use App\Models\ImpactMetric;
use App\Models\Purchase;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class ImpactPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_dampak_page_is_accessible_and_renders_successfully(): void
    {
        $response = $this->get('/dampak');

        $response->assertOk();
        $response->assertViewIs('user.pages.dampak');
        $response->assertViewHasAll([
            'impactMetrics',
            'financialData',
            'lastUpdated',
        ]);
        $response->assertViewMissing('downloadableReports');
        $response->assertSee('Dampak &amp; Transparansi', false);
        $response->assertSee('Laporan dana');
        $response->assertDontSee('Laporan untuk diunduh');
    }

    public function test_dampak_page_displays_database_impact_metrics(): void
    {
        $this->seed(DatabaseSeeder::class);

        $response = $this->get('/dampak');

        $response->assertOk();
        $metric = ImpactMetric::first();
        $this->assertNotNull($metric);
        $response->assertSee($metric->label);
        $response->assertSee($metric->value);
    }

    public function test_dampak_page_renders_cleanly_when_data_is_empty(): void
    {
        // When there is no financial data
        FinancialReportService::clearCache();

        $response = $this->get('/dampak');

        $response->assertOk();
        $response->assertSee('Belum ada data keuangan');
        $response->assertViewHas('financialData', function ($data) {
            return $data['income'] === 0
                && $data['expense'] === 0
                && $data['balance'] === 0
                && $data['all_zero'] === true
                && ! empty($data['periods']);
        });
    }

    public function test_dampak_page_incorporates_paid_purchases_and_expenses_realtime(): void
    {
        FinancialReportService::clearCache();

        $now = Carbon::now();

        // Completed purchase
        Purchase::create([
            'order_code' => 'ORD-IMPACT-001',
            'customer_name' => 'Donatur Dermawan',
            'customer_phone' => '081234567890',
            'total_price' => 500000,
            'status' => 'completed',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // Paid purchase
        Purchase::create([
            'order_code' => 'ORD-IMPACT-002',
            'customer_name' => 'Pembeli Baik',
            'customer_phone' => '081234567891',
            'total_price' => 250000,
            'status' => 'paid',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // Pending purchase (should NOT be counted)
        Purchase::create([
            'order_code' => 'ORD-IMPACT-003',
            'customer_name' => 'Pending Order',
            'customer_phone' => '081234567892',
            'total_price' => 100000,
            'status' => 'pending',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // Expense
        Expense::create([
            'spent_at' => $now->format('Y-m-d'),
            'description' => 'Biaya pelatihan kriya ABK',
            'category' => 'Terapi',
            'amount' => 300000,
        ]);

        $response = $this->get('/dampak');

        $response->assertOk();
        $response->assertViewHas('financialData', function ($data) {
            // Income = 500.000 + 250.000 = 750.000
            // Expense = 300.000
            // Balance = 450.000
            return $data['income'] === 750000
                && $data['expense'] === 300000
                && $data['balance'] === 450000
                && $data['all_zero'] === false;
        });

        $response->assertSee('750.000');
        $response->assertSee('300.000');
        $response->assertSee('450.000');
    }

    public function test_dampak_page_supports_period_filtering(): void
    {
        FinancialReportService::clearCache();

        $pastDate = Carbon::now()->subMonths(2);

        Purchase::create([
            'order_code' => 'ORD-PAST-001',
            'customer_name' => 'Donatur Masa Lalu',
            'customer_phone' => '081234567899',
            'total_price' => 1000000,
            'status' => 'completed',
            'created_at' => $pastDate,
            'updated_at' => $pastDate,
        ]);

        $periodKey = sprintf('%04d-%02d', $pastDate->year, $pastDate->month);

        $response = $this->get('/dampak?periode='.$periodKey);

        $response->assertOk();
        $response->assertViewHas('financialData', function ($data) use ($periodKey) {
            return $data['selected_period'] === $periodKey
                && $data['income'] === 1000000;
        });
    }
}
