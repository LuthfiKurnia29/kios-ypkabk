<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * Test the catalog page returns a successful response.
     */
    public function test_katalog_page_returns_a_successful_response(): void
    {
        $response = $this->get('/katalog');

        $response->assertStatus(200);
        $response->assertSee('Etalase Digital Kios Mandiri YPKABK');
    }
}
