<?php

namespace App\Http\Services;

use App\Models\DetailPurchase;
use App\Models\Donation;
use App\Models\Purchase;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class SiteStatsService extends BaseService
{
    /**
     * Cache key for aggregated site stats.
     */
    public const CACHE_KEY = 'site_stats';

    /**
     * Cache duration (max 1 minute per requirement).
     */
    private const CACHE_TTL = 60;

    /**
     * Get all real-time stats with 1-minute caching.
     *
     * @return array{
     *     dana_terapi: float,
     *     paid_purchases_count: int,
     *     barang_tersalurkan: int,
     *     donations_received_count: int,
     *     donations_items_sum: int,
     *     sold_items_sum: int,
     *     abk_trained_count: int,
     * }
     */
    public function getStats(): array
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            // 1. Dana terapi = Total purchases berstatus paid & completed (pesanan cancelled diabaikan)
            $paidPurchasesQuery = Purchase::whereIn('status', ['paid', 'completed']);
            $paidPurchasesCount = (int) $paidPurchasesQuery->count();
            $danaTotal = (float) $paidPurchasesQuery->sum('total_price');

            // 2. Barang tersalurkan = SUM(item_count) donasi received + SUM(quantity) detail_purchases paid/completed
            $donationsReceivedQuery = Donation::where('status', 'received');
            $donationsReceivedCount = (int) $donationsReceivedQuery->count();

            if (Schema::hasColumn('donations', 'item_count')) {
                $donationsItemsSum = (int) $donationsReceivedQuery->sum('item_count');
            } else {
                $donationsItemsSum = $donationsReceivedCount;
            }

            $soldItemsSum = (int) DetailPurchase::whereHas('purchase', function ($q) {
                $q->whereIn('status', ['paid', 'completed']);
            })->sum('quantity');

            $totalBarangTersalurkan = $donationsItemsSum + $soldItemsSum;

            // 3. ABK terlatih mandiri = Diambil dari site_settings (key: abk_trained_count, default: 0)
            $abkCount = (int) SiteSetting::get('abk_trained_count', 0);

            return [
                'dana_terapi' => $danaTotal,
                'paid_purchases_count' => $paidPurchasesCount,
                'barang_tersalurkan' => $totalBarangTersalurkan,
                'donations_received_count' => $donationsReceivedCount,
                'donations_items_sum' => $donationsItemsSum,
                'sold_items_sum' => $soldItemsSum,
                'abk_trained_count' => $abkCount,
            ];
        });
    }

    /**
     * Get 3 unified impact cards for both Home and /dampak based on real data.
     *
     * @return array<int, array{category: string, value: string, label: string, description: string, is_highlight: bool}>
     */
    public function getSummaryCards(): array
    {
        $stats = $this->getStats();

        $barangFormatted = self::formatNumber($stats['barang_tersalurkan']);
        $abkCount = $stats['abk_trained_count'];
        $abkFormatted = $abkCount > 0 ? "{$abkCount} ABK" : '0 ABK';
        $danaFormatted = self::formatRupiah($stats['dana_terapi']);

        return [
            [
                'category' => 'Penyaluran',
                'value' => $barangFormatted,
                'label' => 'Barang tersalurkan',
                'description' => 'Barang preloved terkurasi yang diterima pemilik baru dan tidak berakhir sebagai sampah.',
                'is_highlight' => false,
            ],
            [
                'category' => 'Penerima Manfaat',
                'value' => $abkFormatted,
                'label' => 'Terlatih mandiri',
                'description' => 'Remaja dengan autisme, down syndrome, dan disabilitas fisik kini aktif berkarya kriya dan menjahit.',
                'is_highlight' => false,
            ],
            [
                'category' => 'Alokasi Dana',
                'value' => $danaFormatted,
                'label' => 'Dana terapi terkumpul',
                'description' => 'Seluruh dana dari penjualan dan donasi digunakan untuk operasional terapi dan pelatihan vokasi anak binaan.',
                'is_highlight' => true,
            ],
        ];
    }

    /**
     * Format a rupiah amount for display (e.g. "Rp 84,5 Jt" or "Rp 150.000").
     */
    public static function formatRupiah(float $amount): string
    {
        if ($amount <= 0) {
            return 'Rp 0';
        }

        if ($amount >= 1_000_000_000) {
            $value = $amount / 1_000_000_000;

            return 'Rp '.rtrim(rtrim(number_format($value, 1, ',', '.'), '0'), ',').' M';
        }

        if ($amount >= 1_000_000) {
            $value = $amount / 1_000_000;

            return 'Rp '.rtrim(rtrim(number_format($value, 1, ',', '.'), '0'), ',').' Jt';
        }

        return 'Rp '.number_format($amount, 0, ',', '.');
    }

    /**
     * Format an item count for display (e.g. "1.420+" or "15").
     */
    public static function formatNumber(int $count): string
    {
        if ($count >= 1000) {
            return number_format($count, 0, ',', '.').'+';
        }

        return number_format($count, 0, ',', '.');
    }

    /**
     * Manually clear the stats cache.
     */
    public static function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
