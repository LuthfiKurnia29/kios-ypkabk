<?php

namespace App\Http\Controllers;

use App\Models\DetailPurchase;
use App\Models\Donation;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    /**
     * Display the reports overview page (admin-only).
     */
    public function index(Request $request): View
    {
        $bulan = (int) $request->get('bulan', now()->month);
        $tahun = (int) $request->get('tahun', now()->year);

        // Clamp to valid values
        $bulan = max(1, min(12, $bulan));
        $tahun = max(2020, min((int) now()->year + 1, $tahun));

        // --- Laporan Penjualan ---
        $purchaseQuery = Purchase::whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun);

        $totalTransaksi = (clone $purchaseQuery)->count();
        $totalPendapatan = (clone $purchaseQuery)
            ->whereIn('status', ['paid', 'completed'])
            ->sum('total_price');
        $totalPending = (clone $purchaseQuery)->where('status', 'pending')->count();
        $totalCancelled = (clone $purchaseQuery)->where('status', 'cancelled')->count();
        $totalPaid = (clone $purchaseQuery)->whereIn('status', ['paid', 'completed'])->count();

        // Top 5 produk terlaku dalam periode
        $topProduk = DetailPurchase::with('product')
            ->whereHas('purchase', function ($q) use ($bulan, $tahun) {
                $q->whereIn('status', ['paid', 'completed'])
                    ->whereMonth('created_at', $bulan)
                    ->whereYear('created_at', $tahun);
            })
            ->selectRaw('product_id, SUM(quantity) as total_terjual, SUM(total_price) as total_pendapatan')
            ->groupBy('product_id')
            ->orderByDesc('total_terjual')
            ->limit(5)
            ->get();

        // Ringkasan penjualan per bulan (12 bulan terakhir)
        $penjualanPerBulan = $this->getPenjualanPerBulan();

        // --- Laporan Donasi ---
        $donationQuery = Donation::whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun);

        $totalDonasi = (clone $donationQuery)->count();
        $donasiDiterima = (clone $donationQuery)->where('status', 'received')->count();
        $donasiPending = (clone $donationQuery)->whereIn('status', ['pending', 'confirmed', 'picked_up'])->count();
        $donasiDitolak = (clone $donationQuery)->where('status', 'rejected')->count();

        // Ringkasan donasi per bulan (12 bulan terakhir)
        $donasiPerBulan = $this->getDonasiPerBulan();

        return view('admin.pages.reports', compact(
            'bulan',
            'tahun',
            'totalTransaksi',
            'totalPendapatan',
            'totalPending',
            'totalCancelled',
            'totalPaid',
            'topProduk',
            'penjualanPerBulan',
            'totalDonasi',
            'donasiDiterima',
            'donasiPending',
            'donasiDitolak',
            'donasiPerBulan',
        ));
    }

    /**
     * Collect monthly sales summary for the past 12 months.
     *
     * @return array<int, array{bulan: string, total: int, pendapatan: float, cancelled: int}>
     */
    private function getPenjualanPerBulan(): array
    {
        $rows = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $m = (int) $date->month;
            $y = (int) $date->year;

            $q = Purchase::whereMonth('created_at', $m)->whereYear('created_at', $y);

            $rows[] = [
                'bulan' => $date->translatedFormat('M Y'),
                'total' => (clone $q)->count(),
                'pendapatan' => (float) (clone $q)->whereIn('status', ['paid', 'completed'])->sum('total_price'),
                'cancelled' => (clone $q)->where('status', 'cancelled')->count(),
            ];
        }

        return $rows;
    }

    /**
     * Collect monthly donation summary for the past 12 months.
     *
     * @return array<int, array{bulan: string, total: int, diterima: int, ditolak: int}>
     */
    private function getDonasiPerBulan(): array
    {
        $rows = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $m = (int) $date->month;
            $y = (int) $date->year;

            $q = Donation::whereMonth('created_at', $m)->whereYear('created_at', $y);

            $rows[] = [
                'bulan' => $date->translatedFormat('M Y'),
                'total' => (clone $q)->count(),
                'diterima' => (clone $q)->where('status', 'received')->count(),
                'ditolak' => (clone $q)->where('status', 'rejected')->count(),
            ];
        }

        return $rows;
    }
}
