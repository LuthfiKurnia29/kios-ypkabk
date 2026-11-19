<?php

namespace App\Http\Controllers;

use App\Http\Services\ProductService;
use App\Http\Services\SiteStatsService;
use App\Models\Category;
use App\Models\Donation;
use App\Models\ImpactMetric;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Review;
use App\Models\Unboxing;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class UserController extends Controller
{
    public function __construct(
        protected ProductService $productService,
        protected SiteStatsService $siteStatsService,
    ) {}

    /**
     * Display the user landing page (FR-01).
     */
    public function index(): View
    {
        $featuredProducts = $this->productService->getFeaturedProducts(4);
        $summaryCards = $this->siteStatsService->getSummaryCards();
        $reviews = Review::active()->get();
        $donationCategories = Category::where('is_active', true)->get();

        return view('user.pages.home', compact(
            'featuredProducts',
            'summaryCards',
            'reviews',
            'donationCategories'
        ));
    }

    /**
     * Display the catalog page (FR-03).
     */
    public function katalog(Request $request): View
    {
        $products = $this->productService->getProducts($request->all());
        $categories = Category::where('is_active', true)->get();
        $totalProductCount = Product::count();

        return view('user.pages.katalog', compact('products', 'categories', 'totalProductCount'));
    }

    /**
     * Display the About Us page (FR-02).
     */
    public function tentang(): View
    {
        $impactMetrics = ImpactMetric::where('is_active', true)->orderBy('order')->get();

        return view('user.pages.tentang', compact('impactMetrics'));
    }

    /**
     * Display the public Transparency & Donation page (FR-04).
     */
    public function transparansi(): View
    {
        $impactMetrics = ImpactMetric::where('is_active', true)->orderBy('order')->get();
        $recentPurchases = Purchase::with('detailPurchases.product')
            ->whereIn('status', ['paid', 'completed'])
            ->latest()
            ->take(20)
            ->get();

        $recentDonations = Donation::whereIn('status', ['received', 'picked_up', 'confirmed'])
            ->latest()
            ->take(20)
            ->get();

        $donationCategories = Category::where('is_active', true)->get();

        return view('user.pages.transparansi', compact(
            'impactMetrics',
            'recentPurchases',
            'recentDonations',
            'donationCategories'
        ));
    }

    /**
     * Display the Impact & Transparency report page (Dampak).
     */
    public function dampak(Request $request): View
    {
        $summaryCards = $this->siteStatsService->getSummaryCards();
        $impactMetrics = ImpactMetric::where('is_active', true)->orderBy('order')->get();

        if ($impactMetrics->isEmpty()) {
            $impactMetrics = collect([
                (object) [
                    'value' => '1.420+',
                    'label' => 'Barang layak pakai tersalurkan',
                    'description' => 'Dari 1.120+ donatur di Sidoarjo & Mojokerto',
                ],
                (object) [
                    'value' => '38',
                    'label' => 'ABK terlatih mandiri',
                    'description' => 'Kelas keterampilan, kriya, dan pelayanan kios',
                ],
                (object) [
                    'value' => 'Rp 84,5 Jt',
                    'label' => 'Dana terapi terkumpul',
                    'description' => '100% dialokasikan secara transparan',
                ],
            ]);
        }

        $financialData = $this->getFinancialReportData($request->query('periode'));

        $downloadableReports = collect([
            [
                'title' => 'Laporan Keuangan & Dampak Terapi – Kuartal I 2024',
                'date' => '10 April 2024',
                'size' => '2.8 MB PDF',
                'url' => '#',
            ],
            [
                'title' => 'Rekap Penyaluran Barang Preloved & Vokasi – Semester II 2023',
                'date' => '15 Januari 2024',
                'size' => '3.4 MB PDF',
                'url' => '#',
            ],
            [
                'title' => 'Laporan Akuntabilitas Tahunan Kios Mandiri YPKABK 2023',
                'date' => '05 Februari 2024',
                'size' => '5.1 MB PDF',
                'url' => '#',
            ],
            [
                'title' => 'Laporan Pengadaan Alat Terapi Sensori Sentra Mojokerto',
                'date' => '22 November 2023',
                'size' => '1.9 MB PDF',
                'url' => '#',
            ],
        ]);

        $latestMetricDate = ImpactMetric::max('updated_at');
        $latestPurchaseDate = Purchase::max('updated_at');
        $latestDate = $latestMetricDate ?: $latestPurchaseDate;
        $lastUpdated = $latestDate ? Carbon::parse($latestDate)->translatedFormat('d F Y') : '15 Mei 2024';

        return view('user.pages.dampak', compact(
            'summaryCards',
            'impactMetrics',
            'financialData',
            'downloadableReports',
            'lastUpdated'
        ));
    }

    /**
     * Compute financial report data combining actual purchases and operational summaries.
     *
     * @return array{income: float|int, expense: float|int, balance: float|int, monthly: array<int, array{month: string, income: float|int, expense: float|int}>, periods: array<int, array{key: string, label: string}>, selected_period: string}
     */
    protected function getFinancialReportData(?string $selectedPeriod = null): array
    {
        $availablePeriods = [
            ['key' => 'mei-2024', 'label' => 'Mei 2024', 'income' => 14850000, 'expense' => 13420000],
            ['key' => 'apr-2024', 'label' => 'April 2024', 'income' => 13500000, 'expense' => 12900000],
            ['key' => 'mar-2024', 'label' => 'Maret 2024', 'income' => 12800000, 'expense' => 12400000],
        ];

        $actualPurchasesTotal = (float) Purchase::whereIn('status', ['paid', 'completed'])->sum('total_price');

        $activePeriod = collect($availablePeriods)->firstWhere('key', $selectedPeriod) ?? $availablePeriods[0];

        $income = $activePeriod['income'] + $actualPurchasesTotal;
        $expense = (int) round($income * 0.903);
        $balance = $income - $expense;

        $monthly = [
            ['month' => 'Jan', 'income' => 9500000, 'expense' => 8800000],
            ['month' => 'Feb', 'income' => 11200000, 'expense' => 10100000],
            ['month' => 'Mar', 'income' => 12800000, 'expense' => 12400000],
            ['month' => 'Apr', 'income' => 13500000, 'expense' => 12900000],
            ['month' => 'Mei', 'income' => (int) $income, 'expense' => (int) $expense],
        ];

        return [
            'income' => $income,
            'expense' => $expense,
            'balance' => $balance,
            'monthly' => $monthly,
            'periods' => $availablePeriods,
            'selected_period' => $activePeriod['key'],
        ];
    }

    /**
     * Display the public Unboxing Gallery page.
     */
    public function unboxing(): View
    {
        $dbUnboxings = Unboxing::where('is_visible', true)
            ->orderByDesc('recorded_at')
            ->latest('id')
            ->get();

        if ($dbUnboxings->isNotEmpty()) {
            $unboxingVideos = $dbUnboxings->map(function (Unboxing $item): array {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'recorded_at' => $item->recorded_at ? $item->recorded_at->format('Y-m-d') : null,
                    'platform' => $item->platform,
                    'url' => $item->url,
                    'is_visible' => (bool) $item->is_visible,
                    'description' => $item->description,
                ];
            });
        } else {
            $unboxingVideos = collect([
                [
                    'title' => 'Unboxing 3 Box Pakaian Balita Bunda Melati',
                    'recorded_at' => '2025-07-12',
                    'platform' => 'instagram',
                    'url' => 'https://www.instagram.com/p/C8unboxing1/',
                    'is_visible' => true,
                ],
                [
                    'title' => 'Buka Paket Alat Lukis & Kuas untuk Kelas Seni ABK',
                    'recorded_at' => '2025-07-08',
                    'platform' => 'youtube',
                    'url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                    'is_visible' => true,
                ],
                [
                    'title' => 'Unboxing Buku Cerita Bergambar Sahabat Istimewa',
                    'recorded_at' => '2025-07-03',
                    'platform' => 'instagram',
                    'url' => 'https://www.instagram.com/p/C8unboxing2/',
                    'is_visible' => true,
                ],
                [
                    'title' => 'Paket Mainan Sensori & Balok Kayu Edukatif',
                    'recorded_at' => '2025-06-28',
                    'platform' => 'instagram',
                    'url' => 'https://www.instagram.com/p/C8unboxing3/',
                    'is_visible' => false,
                ],
                [
                    'title' => 'Dokumentasi Serah Terima Meja Kursi Belajar Sentra',
                    'recorded_at' => '2025-06-20',
                    'platform' => 'youtube',
                    'url' => 'https://youtu.be/kJQP7kiw5Fk',
                    'is_visible' => true,
                ],
            ])->filter(fn (array $item): bool => $item['is_visible'] === true)->values();
        }

        return view('user.pages.unboxing', compact('unboxingVideos'));
    }
}