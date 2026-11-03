<?php

namespace Tests\Feature;

use App\Http\Services\DonationService;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DonationServiceTest extends TestCase
{
    use RefreshDatabase;

    protected DonationService $donationService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->donationService = app(DonationService::class);
    }

    public function test_create_and_update_donation_status(): void
    {
        $category = Category::create([
            'name' => 'Buku Anak',
            'slug' => 'buku-anak',
            'type' => 'donation',
        ]);

        $donation = $this->donationService->createDonation([
            'donor_name' => 'Ahmad',
            'donor_phone' => '0855667788',
            'donor_city' => 'Surabaya',
            'category_id' => $category->id,
            'description' => '3 kardus buku',
        ]);

        $this->assertEquals('pending', $donation->status);
        $this->assertEquals($category->name, $donation->category_name);
        $this->assertNull($donation->received_at);

        $updated = $this->donationService->updateDonationStatus($donation->id, 'received');
        $this->assertEquals('received', $updated->status);
        $this->assertNotNull($updated->received_at);
    }
}
