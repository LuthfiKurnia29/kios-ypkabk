<?php

namespace Tests\Feature;

use App\Http\Services\PaymentSettingService;
use App\Http\Services\ProductService;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPaymentSettingsTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected PaymentSettingService $paymentService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->admin()->create();
        $this->paymentService = app(PaymentSettingService::class);
    }

    public function test_guest_cannot_access_payment_settings_page(): void
    {
        $response = $this->get(route('admin.payment-settings.index'));
        $response->assertRedirect(route('login'));

        $user = User::factory()->create(['role' => 'user']);
        $userResponse = $this->actingAs($user)->get(route('admin.payment-settings.index'));
        $userResponse->assertForbidden();
    }

    public function test_admin_can_view_payment_settings_page(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.payment-settings.index'));

        $response->assertOk();
        $response->assertSee('Metode Pembayaran');
        $response->assertSee('QRIS Dinamis');
        $response->assertSee('Transfer Bank Manual');
        $response->assertSee('Bank Central Asia (BCA)');
    }

    public function test_admin_can_update_qris_settings(): void
    {
        $response = $this->actingAs($this->admin)->put(route('admin.payment-settings.update-qris'), [
            'payment_qris_enabled' => '1',
            'payment_qris_merchant_name' => 'Kios Kebaikan YPKABK Official',
            'payment_qris_base_string' => '00020101021126600016ID.CO.QRIS.WWW0118936009990000000000021000000000000303UMI51440014ID.CO.QRIS.WWW02150000000000000000303UMI5204581453033605802ID5920KIOS MANDIRI YPKABK6008SIDOARJO61056121862070703A016304',
        ]);

        $response->assertRedirect(route('admin.payment-settings.index'));
        $response->assertSessionHas('success_qris');

        $this->assertEquals('1', SiteSetting::get('payment_qris_enabled'));
        $this->assertEquals('Kios Kebaikan YPKABK Official', $this->paymentService->getQrisMerchantName());
    }

    public function test_admin_can_toggle_bank_transfer_method(): void
    {
        $response = $this->actingAs($this->admin)->put(route('admin.payment-settings.update-bank'), [
            'payment_bank_enabled' => '0',
        ]);

        $response->assertRedirect(route('admin.payment-settings.index'));
        $response->assertSessionHas('success_bank');

        $this->assertFalse($this->paymentService->isBankTransferEnabled());
    }

    public function test_admin_can_add_edit_toggle_and_delete_bank_account(): void
    {
        // 1. Tambah Bank Baru (BSI)
        $addResponse = $this->actingAs($this->admin)->post(route('admin.payment-settings.banks.store'), [
            'code' => 'BSI',
            'name' => 'Bank Syariah Indonesia (BSI)',
            'short_name' => 'BSI',
            'account_number' => '711-2233-445',
            'account_holder' => 'Yayasan Peduli Kasih ABK',
        ]);

        $addResponse->assertRedirect(route('admin.payment-settings.index'));
        $addResponse->assertSessionHas('success_bank_account');

        $accounts = $this->paymentService->getBankAccounts(false);
        $this->assertArrayHasKey('BSI', $accounts);
        $this->assertEquals('711-2233-445', $accounts['BSI']['account_number']);
        $this->assertEquals('7112233445', $accounts['BSI']['account_number_raw']);

        // 2. Edit Bank
        $editResponse = $this->actingAs($this->admin)->put(route('admin.payment-settings.banks.update', 'BSI'), [
            'name' => 'Bank Syariah Indonesia Cabang Sidoarjo',
            'short_name' => 'BSI Sidoarjo',
            'account_number' => '799-8877-665',
            'account_holder' => 'Yayasan Peduli Kasih ABK Rekening Utama',
            'is_active' => '1',
        ]);

        $editResponse->assertRedirect(route('admin.payment-settings.index'));
        $accountsAfterEdit = $this->paymentService->getBankAccounts(false);
        $this->assertEquals('799-8877-665', $accountsAfterEdit['BSI']['account_number']);
        $this->assertEquals('Yayasan Peduli Kasih ABK Rekening Utama', $accountsAfterEdit['BSI']['account_holder']);

        // 3. Toggle Status
        $toggleResponse = $this->actingAs($this->admin)->patch(route('admin.payment-settings.banks.toggle', 'BSI'));
        $toggleResponse->assertRedirect(route('admin.payment-settings.index'));
        $accountsAfterToggle = $this->paymentService->getBankAccounts(false);
        $this->assertFalse($accountsAfterToggle['BSI']['is_active']);

        // 4. Delete Bank
        $deleteResponse = $this->actingAs($this->admin)->delete(route('admin.payment-settings.banks.destroy', 'BSI'));
        $deleteResponse->assertRedirect(route('admin.payment-settings.index'));
        $accountsAfterDelete = $this->paymentService->getBankAccounts(false);
        $this->assertArrayNotHasKey('BSI', $accountsAfterDelete);
    }

    public function test_checkout_respects_dynamic_payment_settings(): void
    {
        $product = app(ProductService::class)->createDataProduct([
            'name' => 'Pouch Rajut Cantik',
            'price' => 35000,
            'stock' => 10,
        ]);

        // Nonaktifkan QRIS, hanya Bank Transfer aktif
        $this->paymentService->saveQrisSettings(['payment_qris_enabled' => '0']);
        $this->paymentService->saveBankAccounts($this->paymentService->getBankAccounts(), true);

        $this->assertFalse($this->paymentService->isQrisEnabled());
        $this->assertTrue($this->paymentService->isBankTransferEnabled());
        $this->assertEquals(['bank_transfer'], $this->paymentService->getActivePaymentMethods());

        // Checkout dengan transfer bank berhasil
        $response = $this->postJson(route('purchases.store'), [
            'customer_name' => 'Budi Santoso',
            'customer_phone' => '081234567890',
            'customer_address' => 'Surabaya',
            'payment_method' => 'bank_transfer',
            'bank_name' => 'BCA',
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 1,
                    'price' => 35000,
                ],
            ],
        ]);

        $response->assertStatus(201);
        $this->assertEquals('bank_transfer', $response->json('payment_method'));
    }
}
