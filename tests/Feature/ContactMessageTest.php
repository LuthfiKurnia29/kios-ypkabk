<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactMessageTest extends TestCase
{
    use RefreshDatabase;

    public function test_valid_contact_message_is_stored_and_redirects_with_success(): void
    {
        $payload = [
            'name' => 'Budi Santoso',
            'phone' => '081234567890',
            'message' => 'Halo, saya ingin menanyakan jadwal penjemputan donasi pakaian di daerah Sidoarjo.',
            'website' => '',
        ];

        $response = $this->post(route('contact.store'), $payload);

        $response->assertRedirect(route('kontak'));
        $response->assertSessionHas('success', 'Pesan Anda sudah kami terima, kami balas dalam 1x24 jam.');

        $this->assertDatabaseHas('contact_messages', [
            'name' => 'Budi Santoso',
            'phone' => '081234567890',
            'message' => 'Halo, saya ingin menanyakan jadwal penjemputan donasi pakaian di daerah Sidoarjo.',
            'status' => 'new',
        ]);
    }

    public function test_empty_input_is_rejected_with_validation_errors(): void
    {
        $response = $this->post(route('contact.store'), [
            'name' => '',
            'phone' => '',
            'message' => '',
        ]);

        $response->assertSessionHasErrors(['name', 'phone', 'message']);
        $this->assertDatabaseCount('contact_messages', 0);
    }

    public function test_invalid_phone_number_is_rejected(): void
    {
        $response = $this->post(route('contact.store'), [
            'name' => 'Budi Santoso',
            'phone' => '12345',
            'message' => 'Halo, ini pesan percobaan dengan pesan lebih dari 10 karakter.',
        ]);

        $response->assertSessionHasErrors(['phone']);
        $this->assertDatabaseCount('contact_messages', 0);
    }

    public function test_short_message_is_rejected(): void
    {
        $response = $this->post(route('contact.store'), [
            'name' => 'Budi Santoso',
            'phone' => '081234567890',
            'message' => 'Halo',
        ]);

        $response->assertSessionHasErrors(['message']);
        $this->assertDatabaseCount('contact_messages', 0);
    }

    public function test_honeypot_field_is_rejected_and_not_stored(): void
    {
        $response = $this->post(route('contact.store'), [
            'name' => 'Spam Bot',
            'phone' => '081234567890',
            'message' => 'Ini pesan spam otomatis yang mencoba mengisi semua field.',
            'website' => 'http://spamsite.com',
        ]);

        $this->assertDatabaseCount('contact_messages', 0);
    }

    public function test_guest_cannot_access_admin_messages(): void
    {
        $response = $this->get(route('admin.messages.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_regular_user_cannot_access_admin_messages(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->get(route('admin.messages.index'));

        $response->assertForbidden();
    }

    public function test_admin_can_view_messages_list_and_details(): void
    {
        $admin = User::factory()->admin()->create();

        $msg = ContactMessage::create([
            'name' => 'Siti Rahma',
            'phone' => '081987654321',
            'message' => 'Pertanyaan mengenai donasi mainan anak untuk sentra Mojokerto.',
            'status' => 'new',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.messages.index'));
        $response->assertOk();
        $response->assertSee('Siti Rahma');
        $response->assertSee('081987654321');
        // Saat halaman pesan masuk dibuka, pesan otomatis ditandai sebagai dibaca
        $this->assertEquals('read', $msg->fresh()->status);
        $this->assertNotNull($msg->fresh()->read_at);

        $detailResponse = $this->actingAs($admin)->get(route('admin.messages.show', $msg));
        $detailResponse->assertOk();
        $detailResponse->assertSee('Siti Rahma');
        $detailResponse->assertSee('Pertanyaan mengenai donasi mainan anak untuk sentra Mojokerto.');
        $detailResponse->assertSee('wa.me/6281987654321');
    }

    public function test_admin_can_toggle_read_status(): void
    {
        $admin = User::factory()->admin()->create();

        $msg = ContactMessage::create([
            'name' => 'Dewi',
            'phone' => '081234567890',
            'message' => 'Pesan detail konsultasi donasi.',
            'status' => 'new',
        ]);

        $response = $this->actingAs($admin)->patch(route('admin.messages.toggle-read', $msg));
        $response->assertRedirect();
        $this->assertEquals('read', $msg->fresh()->status);
        $this->assertNotNull($msg->fresh()->read_at);

        $response = $this->actingAs($admin)->patch(route('admin.messages.toggle-read', $msg));
        $response->assertRedirect();
        $this->assertEquals('new', $msg->fresh()->status);
        $this->assertNull($msg->fresh()->read_at);
    }

    public function test_admin_can_delete_message(): void
    {
        $admin = User::factory()->admin()->create();

        $msg = ContactMessage::create([
            'name' => 'Rian',
            'phone' => '081234567890',
            'message' => 'Pesan yang akan dihapus.',
            'status' => 'new',
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.messages.destroy', $msg));
        $response->assertRedirect(route('admin.messages.index'));
        $this->assertDatabaseMissing('contact_messages', ['id' => $msg->id]);
    }
}
