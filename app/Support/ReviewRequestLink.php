<?php

namespace App\Support;

use App\Models\Donation;
use App\Models\Purchase;

class ReviewRequestLink
{
    /**
     * Normalize phone number to international Indonesian format (62...).
     */
    public static function normalizePhone(?string $phone): string
    {
        $digits = preg_replace('/[^0-9]/', '', (string) $phone);

        if (str_starts_with($digits, '0')) {
            $digits = '62'.substr($digits, 1);
        } elseif (str_starts_with($digits, '8')) {
            $digits = '62'.$digits;
        }

        return $digits;
    }

    /**
     * Generate WhatsApp reminder link for a completed/paid purchase.
     */
    public static function forPurchase(Purchase $purchase): ?string
    {
        if (! in_array($purchase->status, ['paid', 'completed'], true)) {
            return null;
        }

        $url = $purchase->reviewUrl();
        if (! $url) {
            return null;
        }

        $phone = self::normalizePhone($purchase->customer_phone);
        $name = trim((string) $purchase->customer_name) ?: 'Kak';
        $message = "Halo {$name}, terima kasih sudah berbelanja di Kios Mandiri YPKABK. Kami ingin mendengar pengalaman Anda, silakan isi di sini: {$url}";

        return 'https://wa.me/'.$phone.'?text='.rawurlencode($message);
    }

    /**
     * Generate WhatsApp reminder link for a received donation.
     */
    public static function forDonation(Donation $donation): ?string
    {
        if ($donation->status !== 'received') {
            return null;
        }

        $url = $donation->reviewUrl();
        if (! $url) {
            return null;
        }

        $phone = self::normalizePhone($donation->donor_phone);
        $name = trim((string) $donation->donor_name) ?: 'Kak';
        $message = "Halo {$name}, terima kasih sudah berdonasi di Kios Mandiri YPKABK. Kami ingin mendengar pengalaman Anda, silakan isi di sini: {$url}";

        return 'https://wa.me/'.$phone.'?text='.rawurlencode($message);
    }
}
