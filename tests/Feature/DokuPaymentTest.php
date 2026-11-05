<?php

namespace Tests\Feature;

use App\Http\Services\DokuService;
use App\Http\Services\ProductService;
use App\Http\Services\PurchaseService;
use App\Models\Category;
use App\Models\Purchase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DokuPaymentTest extends TestCase
{
    use RefreshDatabase;

    protected DokuService $dokuService;

    protected PurchaseService $purchaseService;

    protected ProductService $productService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->productService = app(ProductService::class);
        $this->purchaseService = app(PurchaseService::class);
        $this->dokuService = app(DokuService::class);
    }

    protected function createSamplePurchase(int $stock = 10, int $quantity = 2): Purchase
    {
        $category = Category::create([
            'name' => 'Karya Vokasi ABK',
            'slug' => 'karya-abk',
            'type' => 'product',
        ]);

        $product = $this->productService->createDataProduct([
            'category_id' => $category->id,
            'name' => 'Totebag Lukis ABK',
            'price' => 50000,
            'stock' => $stock,
        ]);

        return $this->purchaseService->createPurchase([
            'customer_name' => 'Ahmad Dahlan',
            'customer_phone' => '081234567890',
            'customer_address' => 'Jl. Pahlawan No. 1, Sidoarjo',
            'notes' => 'Catatan pesanan donasi',
        ], [
            [
                'product_id' => $product->id,
                'quantity' => $quantity,
                'price' => 50000,
            ],
        ]);
    }

    public function test_doku_service_generates_simulator_url_in_dummy_mode(): void
    {
        config(['doku.environment' => 'dummy']);

        $purchase = $this->createSamplePurchase();
        $paymentUrl = $this->dokuService->generatePaymentUrl($purchase);

        $this->assertEquals(route('payment.doku.simulator', ['purchase' => $purchase->order_code]), $paymentUrl);
    }

    public function test_doku_simulator_page_can_be_rendered(): void
    {
        $purchase = $this->createSamplePurchase();

        $response = $this->get(route('payment.doku.simulator', ['purchase' => $purchase->order_code]));

        $response->assertStatus(200);
        $response->assertSee('DOKU');
        $response->assertSee('Mode Simulator DOKU Payment Gateway');
        $response->assertSee($purchase->order_code);
        $response->assertSee('Simulasi Bayar Sukses');
        $response->assertSee('Simulasi Gagal / Batal');
    }

    public function test_simulator_success_action_updates_status_to_paid(): void
    {
        $purchase = $this->createSamplePurchase();

        $response = $this->post(route('payment.doku.simulate-action', ['purchase' => $purchase->order_code]), [
            'action' => 'paid',
            'channel' => 'QRIS',
        ]);

        $response->assertRedirect(route('purchases.success', ['purchase' => $purchase->order_code]));

        $fresh = $purchase->fresh();
        $this->assertEquals('paid', $fresh->status);
        $this->assertEquals('doku', $fresh->payment_method);
        $this->assertEquals('QRIS', $fresh->payment_channel);
        $this->assertNotNull($fresh->paid_at);
        $this->assertNotNull($fresh->payment_reference);

        $this->assertDatabaseHas('log_purchases', [
            'purchase_id' => $purchase->id,
            'action' => 'status_changed_to_paid',
        ]);
    }

    public function test_simulator_cancel_action_cancels_order_and_restores_stock(): void
    {
        $purchase = $this->createSamplePurchase(stock: 10, quantity: 3);

        // Awalnya stock berkurang dari 10 jadi 7 saat order dibuat
        $product = $purchase->detailPurchases()->first()->product;
        $this->assertEquals(7, $product->fresh()->stock);

        $response = $this->post(route('payment.doku.simulate-action', ['purchase' => $purchase->order_code]), [
            'action' => 'cancelled',
            'channel' => 'VIRTUAL_ACCOUNT_BCA',
        ]);

        $response->assertRedirect(route('keranjang'));

        $fresh = $purchase->fresh();
        $this->assertEquals('cancelled', $fresh->status);

        // Verifikasi pengembalian stok kembali menjadi 10
        $this->assertEquals(10, $product->fresh()->stock);

        $this->assertDatabaseHas('log_purchases', [
            'purchase_id' => $purchase->id,
            'action' => 'status_changed_to_cancelled',
        ]);
    }

    public function test_doku_webhook_notification_success_marks_order_as_paid(): void
    {
        $purchase = $this->createSamplePurchase();

        $payload = [
            'order' => [
                'invoice_number' => $purchase->order_code,
                'amount' => 100000,
            ],
            'transaction' => [
                'status' => 'SUCCESS',
                'id' => 'TRX-DOKU-REAL-12345',
            ],
            'channel' => [
                'id' => 'VIRTUAL_ACCOUNT_MANDIRI',
            ],
        ];

        $response = $this->postJson(route('payment.doku.notify'), $payload);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'OK',
            'order_code' => $purchase->order_code,
        ]);

        $fresh = $purchase->fresh();
        $this->assertEquals('paid', $fresh->status);
        $this->assertEquals('VIRTUAL_ACCOUNT_MANDIRI', $fresh->payment_channel);
        $this->assertEquals('TRX-DOKU-REAL-12345', $fresh->payment_reference);
        $this->assertNotNull($fresh->paid_at);
    }

    public function test_doku_webhook_notification_failed_cancels_order_and_restores_stock(): void
    {
        $purchase = $this->createSamplePurchase(stock: 5, quantity: 2);
        $product = $purchase->detailPurchases()->first()->product;
        $this->assertEquals(3, $product->fresh()->stock);

        $payload = [
            'order' => [
                'invoice_number' => $purchase->order_code,
            ],
            'transaction' => [
                'status' => 'FAILED',
                'id' => 'TRX-DOKU-FAIL-999',
            ],
            'channel' => [
                'id' => 'CREDIT_CARD',
            ],
        ];

        $response = $this->postJson(route('payment.doku.notify'), $payload);

        $response->assertStatus(200);

        $fresh = $purchase->fresh();
        $this->assertEquals('cancelled', $fresh->status);
        // Stock harus dikembalikan ke 5
        $this->assertEquals(5, $product->fresh()->stock);
    }

    public function test_payment_success_receipt_page_can_be_rendered(): void
    {
        $purchase = $this->createSamplePurchase();
        $this->dokuService->simulateAction($purchase, 'paid', 'QRIS');

        $response = $this->get(route('purchases.success', ['purchase' => $purchase->order_code]));

        $response->assertStatus(200);
        $response->assertSee('Pembayaran Berhasil Diverifikasi');
        $response->assertSee('LUNAS (PAID)');
        $response->assertSee($purchase->order_code);
        $response->assertSee($purchase->customer_name);
        $response->assertSee('QRIS');
        $response->assertSee('Cetak / Simpan Bukti');
    }

    public function test_payment_checkout_endpoint_returns_json_payment_url(): void
    {
        $purchase = $this->createSamplePurchase();

        $response = $this->getJson(route('payment.doku.checkout', ['purchase' => $purchase->order_code]));

        $response->assertStatus(200);
        $response->assertJson([
            'order_code' => $purchase->order_code,
            'payment_url' => route('payment.doku.simulator', ['purchase' => $purchase->order_code]),
        ]);
    }
}

