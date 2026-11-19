<?php

namespace Tests\Feature;

use App\Models\Unboxing;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminUnboxingAndReportTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->admin()->create();
    }

    public function test_guest_cannot_access_admin_unboxing_page(): void
    {
        $response = $this->get(route('admin.unboxing.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_regular_user_cannot_access_admin_unboxing_page(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->get(route('admin.unboxing.index'));

        $response->assertForbidden();
    }

    public function test_admin_can_view_unboxing_index_page(): void
    {
        Unboxing::create([
            'title' => 'Paket Baju Anak Balita Sentra',
            'url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'platform' => 'youtube',
            'recorded_at' => '2025-07-15',
            'is_visible' => true,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.unboxing.index'));

        $response->assertOk();
        $response->assertSee('Galeri Unboxing');
        $response->assertSee('Paket Baju Anak Balita Sentra');
        $response->assertSee('YouTube');
    }

    public function test_admin_can_add_unboxing_with_nama_barang_and_youtube_link(): void
    {
        $payload = [
            'title' => 'Mainan Sensori Balok Kayu',
            'url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'recorded_at' => '2025-08-01',
            'is_visible' => '1',
            'description' => 'Donasi mainan sensori dari donatur Surabaya.',
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.unboxing.store'), $payload);

        $response->assertRedirect(route('admin.unboxing.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('unboxings', [
            'title' => 'Mainan Sensori Balok Kayu',
            'url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'platform' => 'youtube',
            'is_visible' => true,
        ]);
    }

    public function test_admin_can_add_unboxing_with_nama_barang_and_instagram_link(): void
    {
        $payload = [
            'title' => 'Set Alat Mewarnai Crayon Ramah ABK',
            'url' => 'https://www.instagram.com/reel/C8unboxing789/',
            'recorded_at' => '2025-08-02',
            'is_visible' => '1',
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.unboxing.store'), $payload);

        $response->assertRedirect(route('admin.unboxing.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('unboxings', [
            'title' => 'Set Alat Mewarnai Crayon Ramah ABK',
            'url' => 'https://www.instagram.com/reel/C8unboxing789/',
            'platform' => 'instagram',
            'is_visible' => true,
        ]);
    }

    public function test_admin_can_add_unboxing_using_item_name_field_alias(): void
    {
        $payload = [
            'item_name' => 'Buku Dongeng Sensorik Raba',
            'url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.unboxing.store'), $payload);

        $response->assertRedirect(route('admin.unboxing.index'));
        $this->assertDatabaseHas('unboxings', [
            'title' => 'Buku Dongeng Sensorik Raba',
        ]);
    }

    public function test_validation_fails_when_nama_barang_or_url_is_missing(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.unboxing.store'), [
            'title' => '',
            'url' => '',
        ]);

        $response->assertSessionHasErrors(['title', 'url']);
    }

    public function test_admin_can_update_unboxing_entry(): void
    {
        $unboxing = Unboxing::create([
            'title' => 'Judul Lama',
            'url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'platform' => 'youtube',
            'is_visible' => true,
        ]);

        $updatePayload = [
            'title' => 'Judul Baru Diperbarui',
            'url' => 'https://www.instagram.com/reel/C8unboxingUpdated/',
            'recorded_at' => '2025-08-05',
            'is_visible' => '0',
            'description' => 'Keterangan diperbarui',
        ];

        $response = $this->actingAs($this->admin)->put(route('admin.unboxing.update', $unboxing), $updatePayload);

        $response->assertRedirect(route('admin.unboxing.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('unboxings', [
            'id' => $unboxing->id,
            'title' => 'Judul Baru Diperbarui',
            'url' => 'https://www.instagram.com/reel/C8unboxingUpdated/',
            'platform' => 'instagram',
            'is_visible' => false,
            'description' => 'Keterangan diperbarui',
        ]);
    }

    public function test_admin_can_delete_unboxing_entry(): void
    {
        $unboxing = Unboxing::create([
            'title' => 'Barang Untuk Dihapus',
            'url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'platform' => 'youtube',
            'is_visible' => true,
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.unboxing.destroy', $unboxing));

        $response->assertRedirect(route('admin.unboxing.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('unboxings', [
            'id' => $unboxing->id,
        ]);
    }

    public function test_admin_can_toggle_unboxing_visibility(): void
    {
        $unboxing = Unboxing::create([
            'title' => 'Barang Toggle',
            'url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'platform' => 'youtube',
            'is_visible' => true,
        ]);

        $response = $this->actingAs($this->admin)->patch(route('admin.unboxing.toggle-visibility', $unboxing));

        $response->assertRedirect();
        $this->assertFalse($unboxing->fresh()->is_visible);

        $response = $this->actingAs($this->admin)->patch(route('admin.unboxing.toggle-visibility', $unboxing));
        $this->assertTrue($unboxing->fresh()->is_visible);
    }

    public function test_public_unboxing_page_shows_visible_records_from_database(): void
    {
        Unboxing::create([
            'title' => 'Paket Donasi Kursi Roda Lipat',
            'url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'platform' => 'youtube',
            'is_visible' => true,
            'recorded_at' => '2025-08-10',
        ]);

        Unboxing::create([
            'title' => 'Paket Rahasia Tidak Tampil',
            'url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'platform' => 'youtube',
            'is_visible' => false,
            'recorded_at' => '2025-08-10',
        ]);

        $response = $this->get(route('unboxing'));

        $response->assertOk();
        $response->assertSee('Paket Donasi Kursi Roda Lipat');
        $response->assertDontSee('Paket Rahasia Tidak Tampil');
        $response->assertSee('1 video dokumentasi');
        $response->assertSee('Tonton di YouTube');
        $response->assertDontSee('Unboxing 3 Box Pakaian Balita Bunda Melati');
    }

    public function test_public_unboxing_page_handles_empty_state_without_dummy_data(): void
    {
        Unboxing::query()->delete();

        $response = $this->get(route('unboxing'));

        $response->assertOk();
        $response->assertSee('0 video dokumentasi');
        $response->assertSee('Belum ada dokumentasi unboxing');
        $response->assertDontSee('Unboxing 3 Box Pakaian Balita Bunda Melati');
        $response->assertDontSee('Buka Paket Alat Lukis & Kuas untuk Kelas Seni ABK');
    }
}
