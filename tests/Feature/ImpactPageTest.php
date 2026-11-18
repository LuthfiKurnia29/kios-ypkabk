<?php

namespace Tests\Feature;

use App\Models\ImpactMetric;
use App\Models\Purchase;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
            'downloadableReports',
            'lastUpdated',
        ]);
        $response->assertSee('Dampak &amp; Transparansi', false);
        $response->assertSee('Laporan dana');
        $response->assertSee('Laporan untuk diunduh');
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

    public function test_dampak_page_supports_period_filtering(): void
    {
        $response = $this->get('/dampak?periode=apr-2024');

        $response->assertOk();
        $response->assertSee('April 2024');
        $response->assertViewHas('financialData', function ($data) {
            return $data['selected_period'] === 'apr-2024';
        });
    }

    public function test_dampak_page_incorporates_actual_completed_purchases(): void
    {
        Purchase::create([
            'order_code' => 'ORD-IMPACT-001',
            'customer_name' => 'Donatur Dermawan',
            'total_price' => 150000,
            'status' => 'completed',
        ]);

        $response = $this->get('/dampak');

        $response->assertOk();
        $response->assertViewHas('financialData', function ($data) {
            // Should include the purchase total in the income
            return $data['income'] >= 150000;
        });
    }
}
