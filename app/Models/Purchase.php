<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_code',
        'customer_name',
        'customer_phone',
        'customer_address',
        'address',
        'city',
        'district',
        'village',
        'street_address',
        'total_price',
        'payment_method',
        'bank_name',
        'payment_proof',
        'courier_name',
        'tracking_number',
        'shipping_receipt_path',
        'notes',
        'status',
        'review_token',
        'created_at',
        'updated_at',
    ];

    protected $attributes = [
        'payment_method' => 'qris',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'total_price' => 'decimal:2',
        ];
    }

    /**
     * Get the customer's phone formatted in international format (628...).
     */
    public function getInternationalPhoneAttribute(): string
    {
        $phone = preg_replace('/\D+/', '', (string) $this->customer_phone);

        if (str_starts_with($phone, '0')) {
            return '62'.substr($phone, 1);
        }

        if (str_starts_with($phone, '8')) {
            return '62'.$phone;
        }

        return $phone;
    }

    /**
     * Alias for international phone number.
     */
    public function getFormattedWhatsappNumberAttribute(): string
    {
        return $this->international_phone;
    }

    /**
     * Get the formatted combined full shipping address.
     */
    public function getFullAddressAttribute(): string
    {
        $parts = array_filter([
            $this->street_address,
            $this->village ? 'Desa/Kel. '.$this->village : null,
            $this->district ? 'Kec. '.$this->district : null,
            $this->city,
        ]);

        $structured = implode(', ', $parts);
        $landmark = $this->address ?? $this->customer_address;

        if (! empty($structured) && ! empty($landmark)) {
            if ($landmark !== $structured) {
                return $structured." (Patokan: {$landmark})";
            }

            return $structured;
        }

        return $structured ?: (string) $landmark;
    }

    /**
     * Alias for full_address.
     */
    public function getFormattedAddressAttribute(): string
    {
        return $this->full_address;
    }

    public function getAddressAttribute(): ?string
    {
        return $this->attributes['address'] ?? $this->attributes['customer_address'] ?? null;
    }

    public function setAddressAttribute(?string $value): void
    {
        $this->attributes['address'] = $value;
        if (empty($this->attributes['customer_address'])) {
            $this->attributes['customer_address'] = $value;
        }
    }

    /**
     * Get all supported static bank accounts.
     *
     * @return array<string, array<string, string>>
     */
    public static function getBankAccounts(): array
    {
        return config('kios.bank_accounts', [
            'BCA' => [
                'code' => 'BCA',
                'name' => 'Bank Central Asia (BCA)',
                'short_name' => 'BCA',
                'account_number' => '018-384-9900',
                'account_number_raw' => '0183849900',
                'account_holder' => 'Yayasan Peduli Kasih ABK',
                'color' => 'bg-blue-50 text-blue-800 border-blue-200',
            ],
            'BRI' => [
                'code' => 'BRI',
                'name' => 'Bank Rakyat Indonesia (BRI)',
                'short_name' => 'BRI',
                'account_number' => '009-601-002-345-502',
                'account_number_raw' => '009601002345502',
                'account_holder' => 'Yayasan Peduli Kasih ABK',
                'color' => 'bg-sky-50 text-sky-800 border-sky-200',
            ],
            'Mandiri' => [
                'code' => 'Mandiri',
                'name' => 'Bank Mandiri',
                'short_name' => 'Mandiri',
                'account_number' => '142-00-1988234-1',
                'account_number_raw' => '1420019882341',
                'account_holder' => 'Yayasan Peduli Kasih ABK',
                'color' => 'bg-amber-50 text-amber-800 border-amber-200',
            ],
            'BNI' => [
                'code' => 'BNI',
                'name' => 'Bank Negara Indonesia (BNI)',
                'short_name' => 'BNI',
                'account_number' => '083-912-4567',
                'account_number_raw' => '0839124567',
                'account_holder' => 'Yayasan Peduli Kasih ABK',
                'color' => 'bg-orange-50 text-orange-800 border-orange-200',
            ],
        ]);
    }

    /**
     * Get the bank account details for this order if bank_transfer.
     *
     * @return array<string, string>|null
     */
    public function getSelectedBankAccountAttribute(): ?array
    {
        if ($this->payment_method !== 'bank_transfer') {
            return null;
        }

        $banks = static::getBankAccounts();
        $key = strtoupper((string) ($this->bank_name ?: 'BCA'));

        return $banks[$key] ?? $banks['BCA'] ?? null;
    }

    /**
     * Build the WhatsApp receipt text for this purchase order.
     */
    public function buildWhatsappReceiptText(): string
    {
        $itemsList = '';
        foreach ($this->detailPurchases as $item) {
            $productName = $item->product?->name ?? 'Produk';
            $itemsList .= "• {$productName} (x{$item->quantity}) - Rp ".number_format($item->total_price, 0, ',', '.')."\n";
        }

        if ($this->payment_method === 'bank_transfer') {
            $bank = $this->selected_bank_account;
            $bankName = $bank['name'] ?? ($this->bank_name ? "Transfer Bank {$this->bank_name}" : 'Transfer Bank BCA');
            $accNumber = $bank['account_number'] ?? '018-384-9900';
            $accHolder = $bank['account_holder'] ?? 'Yayasan Peduli Kasih ABK';
            $paymentInfo = "Transfer Bank: {$bankName}\nNo. Rekening: {$accNumber}\na.n. {$accHolder}";
        } else {
            $paymentInfo = 'QRIS Kios Mandiri YPKABK (Scan / Upload Bukti)';
        }

        $orderUrl = route('purchases.show', $this->order_code);
        $dateStr = $this->created_at ? $this->created_at->format('d M Y H:i') : now()->format('d M Y H:i');

        return "Halo {$this->customer_name}, berikut adalah rincian nota pesanan Anda di Kios Mandiri YPKABK:\n\n"
            ."*KODE PESANAN:* {$this->order_code}\n"
            ."*TANGGAL:* {$dateStr}\n\n"
            ."*DAFTAR PRODUK:*\n{$itemsList}\n"
            .'*TOTAL PEMBAYARAN:* Rp '.number_format($this->total_price, 0, ',', '.')."\n"
            ."*METODE PEMBAYARAN:*\n{$paymentInfo}\n\n"
            ."*ALAMAT PENGIRIMAN:*\n{$this->full_address}\n\n"
            ."*TAUTAN DETAIL PESANAN & UPLOAD BUKTI:*\n{$orderUrl}\n\n"
            ."_Simpan tautan ini agar tetap dapat memantau status pesanan kapan saja._\n"
            .'Terima kasih atas kepedulian Anda mendukung karya anak berkebutuhan khusus!';
    }

    /**
     * Get the WhatsApp URL to send the receipt to customer.
     */
    public function getWhatsappReceiptUrlAttribute(): string
    {
        $phone = $this->international_phone;
        $encodedText = rawurlencode($this->buildWhatsappReceiptText());

        return "https://wa.me/{$phone}?text={$encodedText}";
    }

    /**
     * User who made the purchase (if registered).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Detail items in this purchase.
     */
    public function detailPurchases(): HasMany
    {
        return $this->hasMany(DetailPurchase::class);
    }

    /**
     * Audit logs for this purchase.
     */
    public function logPurchases(): HasMany
    {
        return $this->hasMany(LogPurchase::class);
    }

    /**
     * Review submitted for this purchase.
     */
    public function review(): HasOne
    {
        return $this->hasOne(Review::class);
    }

    /**
     * Get or generate the review invitation URL for this purchase.
     * Only valid for paid or completed orders.
     */
    public function reviewUrl(): ?string
    {
        if (! in_array($this->status, ['paid', 'completed'], true)) {
            return null;
        }

        if (empty($this->review_token)) {
            $this->review_token = Str::random(40);
            $this->saveQuietly();
        }

        return route('reviews.create', ['token' => $this->review_token]);
    }
}
