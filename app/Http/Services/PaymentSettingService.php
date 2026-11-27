<?php

namespace App\Http\Services;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Cache;

class PaymentSettingService
{
    public const CACHE_KEY = 'payment_settings_config';

    public const DEFAULT_QRIS_MERCHANT = 'Kios Mandiri YPKABK';

    public const DEFAULT_QRIS_BASE_STRING = '00020101021126600016ID.CO.QRIS.WWW0118936009990000000000021000000000000303UMI51440014ID.CO.QRIS.WWW02150000000000000000303UMI5204581453033605802ID5920KIOS MANDIRI YPKABK6008SIDOARJO61056121862070703A016304';

    /**
     * Determine if QRIS payment method is enabled.
     */
    public function isQrisEnabled(): bool
    {
        $val = SiteSetting::get('payment_qris_enabled');

        return $val === null || (string) $val === '1' || (string) $val === 'true';
    }

    /**
     * Determine if Bank Transfer payment method is enabled.
     */
    public function isBankTransferEnabled(): bool
    {
        $val = SiteSetting::get('payment_bank_enabled');

        return $val === null || (string) $val === '1' || (string) $val === 'true';
    }

    /**
     * Get QRIS merchant name.
     */
    public function getQrisMerchantName(): string
    {
        return (string) (SiteSetting::get('payment_qris_merchant_name')
            ?: config('services.qris.merchant_name', self::DEFAULT_QRIS_MERCHANT));
    }

    /**
     * Get QRIS base string.
     */
    public function getQrisBaseString(): string
    {
        return (string) (SiteSetting::get('payment_qris_base_string')
            ?: config('services.qris.base_string')
            ?: self::DEFAULT_QRIS_BASE_STRING);
    }

    /**
     * Get all bank accounts with optional active filter.
     *
     * @return array<string, array<string, mixed>>
     */
    public function getBankAccounts(bool $onlyActive = false): array
    {
        $raw = SiteSetting::get('payment_bank_accounts');
        $accounts = [];

        if (! empty($raw)) {
            $decoded = is_string($raw) ? json_decode($raw, true) : $raw;
            if (is_array($decoded)) {
                foreach ($decoded as $key => $item) {
                    $code = strtoupper((string) ($item['code'] ?? $key));
                    $accounts[$code] = [
                        'code' => $code,
                        'name' => (string) ($item['name'] ?? $code),
                        'short_name' => (string) ($item['short_name'] ?? $code),
                        'account_number' => (string) ($item['account_number'] ?? ''),
                        'account_number_raw' => preg_replace('/\D+/', '', (string) ($item['account_number_raw'] ?? $item['account_number'] ?? '')),
                        'account_holder' => (string) ($item['account_holder'] ?? 'Yayasan Peduli Kasih ABK'),
                        'color' => (string) ($item['color'] ?? $this->resolveBankColor($code)),
                        'is_active' => isset($item['is_active']) ? (bool) $item['is_active'] : true,
                    ];
                }
            }
        }

        if (empty($accounts)) {
            $defaultConfig = config('kios.bank_accounts', []);
            foreach ($defaultConfig as $key => $item) {
                $code = strtoupper((string) ($item['code'] ?? $key));
                $accounts[$code] = [
                    'code' => $code,
                    'name' => (string) ($item['name'] ?? $code),
                    'short_name' => (string) ($item['short_name'] ?? $code),
                    'account_number' => (string) ($item['account_number'] ?? ''),
                    'account_number_raw' => preg_replace('/\D+/', '', (string) ($item['account_number_raw'] ?? $item['account_number'] ?? '')),
                    'account_holder' => (string) ($item['account_holder'] ?? 'Yayasan Peduli Kasih ABK'),
                    'color' => (string) ($item['color'] ?? $this->resolveBankColor($code)),
                    'is_active' => true,
                ];
            }
        }

        if ($onlyActive) {
            $accounts = array_filter($accounts, fn ($acc) => ! empty($acc['is_active']));
        }

        return $accounts;
    }

    /**
     * Get active payment method keys ('qris', 'bank_transfer').
     *
     * @return array<int, string>
     */
    public function getActivePaymentMethods(): array
    {
        $methods = [];
        if ($this->isQrisEnabled()) {
            $methods[] = 'qris';
        }
        if ($this->isBankTransferEnabled() && count($this->getBankAccounts(true)) > 0) {
            $methods[] = 'bank_transfer';
        }

        return $methods;
    }

    /**
     * Determine the default payment method.
     */
    public function getDefaultPaymentMethod(): string
    {
        $active = $this->getActivePaymentMethods();

        if (in_array('qris', $active, true)) {
            return 'qris';
        }

        if (in_array('bank_transfer', $active, true)) {
            return 'bank_transfer';
        }

        return 'qris';
    }

    /**
     * Get combined public payment configuration for frontend.
     *
     * @return array<string, mixed>
     */
    public function getPaymentConfig(): array
    {
        $activeBanks = $this->getBankAccounts(true);
        $isQris = $this->isQrisEnabled();
        $isBank = $this->isBankTransferEnabled() && count($activeBanks) > 0;

        return [
            'qris_enabled' => $isQris,
            'bank_enabled' => $isBank,
            'default_method' => $this->getDefaultPaymentMethod(),
            'merchant_name' => $this->getQrisMerchantName(),
            'bank_accounts' => $activeBanks,
            'default_bank' => count($activeBanks) > 0 ? array_key_first($activeBanks) : 'BCA',
        ];
    }

    /**
     * Save QRIS settings.
     *
     * @param  array{payment_qris_enabled?: bool|string|int, payment_qris_merchant_name?: ?string, payment_qris_base_string?: ?string}  $data
     */
    public function saveQrisSettings(array $data): void
    {
        if (array_key_exists('payment_qris_enabled', $data)) {
            $val = filter_var($data['payment_qris_enabled'], FILTER_VALIDATE_BOOLEAN) ? '1' : '0';
            SiteSetting::set('payment_qris_enabled', $val);
        }

        if (array_key_exists('payment_qris_merchant_name', $data)) {
            SiteSetting::set('payment_qris_merchant_name', trim((string) $data['payment_qris_merchant_name']));
        }

        if (array_key_exists('payment_qris_base_string', $data)) {
            SiteSetting::set('payment_qris_base_string', trim((string) $data['payment_qris_base_string']));
        }

        $this->clearCache();
    }

    /**
     * Save bank transfer settings and accounts list.
     *
     * @param  array<int|string, array<string, mixed>>  $accounts
     */
    public function saveBankAccounts(array $accounts, ?bool $enabled = null): void
    {
        if ($enabled !== null) {
            SiteSetting::set('payment_bank_enabled', $enabled ? '1' : '0');
        }

        $normalized = [];
        foreach ($accounts as $item) {
            $code = strtoupper(trim((string) ($item['code'] ?? '')));
            if (empty($code)) {
                continue;
            }

            $rawNumber = preg_replace('/\D+/', '', (string) ($item['account_number'] ?? ''));
            $normalized[$code] = [
                'code' => $code,
                'name' => trim((string) ($item['name'] ?? $code)),
                'short_name' => trim((string) ($item['short_name'] ?? $code)),
                'account_number' => trim((string) ($item['account_number'] ?? '')),
                'account_number_raw' => $rawNumber,
                'account_holder' => trim((string) ($item['account_holder'] ?? 'Yayasan Peduli Kasih ABK')),
                'color' => (string) ($item['color'] ?? $this->resolveBankColor($code)),
                'is_active' => isset($item['is_active']) ? (bool) $item['is_active'] : true,
            ];
        }

        SiteSetting::set('payment_bank_accounts', json_encode($normalized, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), 'json');

        $this->clearCache();
    }

    /**
     * Clear cached settings.
     */
    public function clearCache(): void
    {
        SiteSetting::clearCache();
        Cache::forget(self::CACHE_KEY);
    }

    /**
     * Resolve default tailwind color style for bank badge.
     */
    private function resolveBankColor(string $code): string
    {
        return match (strtoupper($code)) {
            'BCA' => 'bg-blue-50 text-blue-800 border-blue-200',
            'BRI' => 'bg-sky-50 text-sky-800 border-sky-200',
            'MANDIRI' => 'bg-amber-50 text-amber-800 border-amber-200',
            'BNI' => 'bg-orange-50 text-orange-800 border-orange-200',
            'BSI' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
            'JATIM' => 'bg-rose-50 text-rose-800 border-rose-200',
            default => 'bg-slate-50 text-slate-800 border-slate-200',
        };
    }
}
