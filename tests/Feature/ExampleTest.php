<?php

namespace Tests\Feature;

use App\Models\Review;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $start = microtime(true);
        $response = $this->get('/');
        $duration = microtime(true) - $start;

        $response->assertStatus(200);
        $response->assertSee('<title>Kios Mandiri YPKABK</title>', false);
        $response->assertDontSee('—');
        $response->assertSee('Tanya Kios (WA)');
        $response->assertSee('viewBox="0 0 24 24"', false);
        $this->assertLessThan(2.0, $duration, "Home page response should be fast, took {$duration}s");
    }

    /**
     * Test the catalog page returns a successful response.
     */
    public function test_katalog_page_returns_a_successful_response(): void
    {
        $response = $this->get('/katalog');

        $response->assertStatus(200);
        $response->assertSee('<title>Katalog</title>', false);
        $response->assertDontSee('—');
    }

    /**
     * Test the home page renders reviews in transparansi section without incomplete object errors.
     */
    public function test_home_page_renders_reviews_successfully(): void
    {
        Review::create([
            'name' => 'Budi Santoso',
            'role' => 'Pembeli Terverifikasi',
            'quote' => 'Pelayanan sangat ramah dan transparan.',
            'source' => 'buyer',
            'is_active' => true,
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Cerita dari Sahabat Kios');
        $response->assertSee('Pelayanan sangat ramah dan transparan.');
        $response->assertSee('Budi S.');
    }
}
