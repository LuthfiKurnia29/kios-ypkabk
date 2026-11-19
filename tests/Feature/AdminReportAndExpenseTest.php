<?php

namespace Tests\Feature;

use App\Models\Expense;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminReportAndExpenseTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->admin()->create();
    }

    public function test_guest_cannot_access_admin_reports_page(): void
    {
        $response = $this->get(route('admin.reports.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_regular_user_cannot_access_admin_reports_page(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->get(route('admin.reports.index'));

        $response->assertForbidden();
    }

    public function test_admin_can_view_reports_page(): void
    {
        Expense::create([
            'spent_at' => now()->format('Y-m-d'),
            'description' => 'Pembelian buku gambar anak binaan',
            'category' => 'Terapi',
            'amount' => 150000,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.reports.index'));

        $response->assertOk();
        $response->assertSee('Laporan Pengeluaran');
        $response->assertSee('Pembelian buku gambar anak binaan');
        $response->assertSee('150.000');
    }

    public function test_admin_can_add_expense(): void
    {
        $payload = [
            'spent_at' => now()->format('Y-m-d'),
            'description' => 'Honor instruktur menjahit ABK',
            'category' => 'Pelatihan',
            'amount' => 500000,
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.reports.store'), $payload);

        $response->assertRedirect(route('admin.reports.index'));
        $response->assertSessionHas('success', 'Pengeluaran berhasil ditambahkan.');

        $this->assertDatabaseHas('expenses', [
            'description' => 'Honor instruktur menjahit ABK',
            'category' => 'Pelatihan',
            'amount' => 500000,
        ]);
    }

    public function test_admin_cannot_add_expense_with_invalid_data(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.reports.store'), [
            'spent_at' => '',
            'description' => '',
            'amount' => '',
        ]);

        $response->assertSessionHasErrors(['spent_at', 'description', 'amount']);
        $this->assertDatabaseCount('expenses', 0);
    }

    public function test_admin_can_update_expense(): void
    {
        $expense = Expense::create([
            'spent_at' => now()->format('Y-m-d'),
            'description' => 'Deskripsi Lama',
            'category' => 'Lama',
            'amount' => 100000,
        ]);

        $updatePayload = [
            'spent_at' => now()->format('Y-m-d'),
            'description' => 'Deskripsi Baru',
            'category' => 'Baru',
            'amount' => 200000,
        ];

        $response = $this->actingAs($this->admin)->put(route('admin.reports.update', $expense), $updatePayload);

        $response->assertRedirect(route('admin.reports.index'));
        $response->assertSessionHas('success', 'Pengeluaran berhasil diperbarui.');

        $this->assertDatabaseHas('expenses', [
            'id' => $expense->id,
            'description' => 'Deskripsi Baru',
            'category' => 'Baru',
            'amount' => 200000,
        ]);
    }

    public function test_admin_can_delete_expense(): void
    {
        $expense = Expense::create([
            'spent_at' => now()->format('Y-m-d'),
            'description' => 'Pengeluaran untuk Dihapus',
            'category' => 'Operasional',
            'amount' => 75000,
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.reports.destroy', $expense));

        $response->assertRedirect(route('admin.reports.index'));
        $response->assertSessionHas('success', 'Pengeluaran berhasil dihapus.');

        $this->assertDatabaseMissing('expenses', [
            'id' => $expense->id,
        ]);
    }
}
