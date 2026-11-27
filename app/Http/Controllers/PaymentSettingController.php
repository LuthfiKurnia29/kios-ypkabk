<?php

namespace App\Http\Controllers;

use App\Http\Services\PaymentSettingService;
use App\Http\Services\PurchaseService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentSettingController extends Controller
{
    public function __construct(
        protected PaymentSettingService $paymentSettingService,
        protected PurchaseService $purchaseService,
    ) {}

    /**
     * Display the payment settings management page.
     */
    public function index(): View
    {
        $qrisEnabled = $this->paymentSettingService->isQrisEnabled();
        $qrisMerchantName = $this->paymentSettingService->getQrisMerchantName();
        $qrisBaseString = $this->paymentSettingService->getQrisBaseString();
        $bankEnabled = $this->paymentSettingService->isBankTransferEnabled();
        $bankAccounts = $this->paymentSettingService->getBankAccounts(false);

        // Sample dynamic QRIS for preview (Rp 10.000)
        $previewPayload = $this->purchaseService->generateDynamicQris(10000, $qrisBaseString);
        $previewQrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=240x240&margin=10&data='.urlencode($previewPayload);

        return view('admin.pages.payment-settings', compact(
            'qrisEnabled',
            'qrisMerchantName',
            'qrisBaseString',
            'bankEnabled',
            'bankAccounts',
            'previewQrUrl'
        ));
    }

    /**
     * Update QRIS payment settings.
     */
    public function updateQris(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'payment_qris_enabled' => ['nullable', 'boolean'],
            'payment_qris_merchant_name' => ['nullable', 'string', 'max:255'],
            'payment_qris_base_string' => ['nullable', 'string', 'max:2000'],
        ], [
            'payment_qris_merchant_name.max' => 'Nama merchant maksimal 255 karakter.',
            'payment_qris_base_string.max' => 'String base QRIS maksimal 2000 karakter.',
        ]);

        $this->paymentSettingService->saveQrisSettings([
            'payment_qris_enabled' => $request->boolean('payment_qris_enabled'),
            'payment_qris_merchant_name' => $validated['payment_qris_merchant_name'] ?? null,
            'payment_qris_base_string' => $validated['payment_qris_base_string'] ?? null,
        ]);

        return redirect()->route('admin.payment-settings.index')
            ->with('success_qris', 'Pengaturan QRIS berhasil disimpan.');
    }

    /**
     * Update Bank Transfer payment overall toggle.
     */
    public function updateBankTransfer(Request $request): RedirectResponse
    {
        $enabled = $request->boolean('payment_bank_enabled');
        $accounts = $this->paymentSettingService->getBankAccounts(false);

        $this->paymentSettingService->saveBankAccounts($accounts, $enabled);

        return redirect()->route('admin.payment-settings.index')
            ->with('success_bank', 'Status metode Transfer Bank berhasil diperbarui.');
    }

    /**
     * Store a newly created bank account.
     */
    public function storeBankAccount(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:20', 'alpha_num'],
            'name' => ['required', 'string', 'max:100'],
            'short_name' => ['nullable', 'string', 'max:30'],
            'account_number' => ['required', 'string', 'max:50'],
            'account_holder' => ['required', 'string', 'max:100'],
        ], [
            'code.required' => 'Kode bank wajib diisi (misal: BCA, BSI).',
            'code.alpha_num' => 'Kode bank hanya boleh berupa huruf dan angka tanpa spasi.',
            'name.required' => 'Nama bank wajib diisi.',
            'account_number.required' => 'Nomor rekening wajib diisi.',
            'account_holder.required' => 'Nama pemilik rekening wajib diisi.',
        ]);

        $code = strtoupper(trim($validated['code']));
        $accounts = $this->paymentSettingService->getBankAccounts(false);

        $accounts[$code] = [
            'code' => $code,
            'name' => trim($validated['name']),
            'short_name' => trim($validated['short_name'] ?: $code),
            'account_number' => trim($validated['account_number']),
            'account_holder' => trim($validated['account_holder']),
            'is_active' => true,
        ];

        $this->paymentSettingService->saveBankAccounts($accounts);

        return redirect()->route('admin.payment-settings.index')
            ->with('success_bank_account', "Rekening bank {$code} berhasil ditambahkan.");
    }

    /**
     * Update an existing bank account.
     */
    public function updateBankAccount(Request $request, string $code): RedirectResponse
    {
        $code = strtoupper($code);
        $accounts = $this->paymentSettingService->getBankAccounts(false);

        if (! isset($accounts[$code])) {
            return redirect()->route('admin.payment-settings.index')
                ->with('error_bank_account', "Rekening bank {$code} tidak ditemukan.");
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'short_name' => ['nullable', 'string', 'max:30'],
            'account_number' => ['required', 'string', 'max:50'],
            'account_holder' => ['required', 'string', 'max:100'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'name.required' => 'Nama bank wajib diisi.',
            'account_number.required' => 'Nomor rekening wajib diisi.',
            'account_holder.required' => 'Nama pemilik rekening wajib diisi.',
        ]);

        $accounts[$code]['name'] = trim($validated['name']);
        $accounts[$code]['short_name'] = trim($validated['short_name'] ?: $code);
        $accounts[$code]['account_number'] = trim($validated['account_number']);
        $accounts[$code]['account_holder'] = trim($validated['account_holder']);
        if ($request->has('is_active')) {
            $accounts[$code]['is_active'] = $request->boolean('is_active');
        }

        $this->paymentSettingService->saveBankAccounts($accounts);

        return redirect()->route('admin.payment-settings.index')
            ->with('success_bank_account', "Rekening bank {$code} berhasil diperbarui.");
    }

    /**
     * Toggle the active status of a bank account.
     */
    public function toggleBankAccount(string $code): RedirectResponse
    {
        $code = strtoupper($code);
        $accounts = $this->paymentSettingService->getBankAccounts(false);

        if (! isset($accounts[$code])) {
            return redirect()->route('admin.payment-settings.index')
                ->with('error_bank_account', "Rekening bank {$code} tidak ditemukan.");
        }

        $accounts[$code]['is_active'] = ! ($accounts[$code]['is_active'] ?? true);
        $this->paymentSettingService->saveBankAccounts($accounts);

        $statusText = $accounts[$code]['is_active'] ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->route('admin.payment-settings.index')
            ->with('success_bank_account', "Rekening bank {$code} berhasil {$statusText}.");
    }

    /**
     * Remove a bank account.
     */
    public function destroyBankAccount(string $code): RedirectResponse
    {
        $code = strtoupper($code);
        $accounts = $this->paymentSettingService->getBankAccounts(false);

        if (isset($accounts[$code])) {
            unset($accounts[$code]);
            $this->paymentSettingService->saveBankAccounts($accounts);
        }

        return redirect()->route('admin.payment-settings.index')
            ->with('success_bank_account', "Rekening bank {$code} berhasil dihapus.");
    }
}
