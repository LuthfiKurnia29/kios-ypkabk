<?php

namespace App\Http\Controllers;

use App\Http\Services\FinancialReportService;
use App\Http\Services\ProductService;
use App\Http\Services\SiteStatsService;
use App\Models\Category;
use App\Models\Donation;
use App\Models\Expense;
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
        protected FinancialReportService $financialReportService,
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
        $stats = $this->siteStatsService->getStats();
        $summaryCards = $this->siteStatsService->getSummaryCards();
        $impactMetrics = ImpactMetric::where('is_active', true)->orderBy('order')->get();

        if ($impactMetrics->isEmpty()) {
            $barangFormatted = SiteStatsService::formatNumber($stats['barang_tersalurkan']);
            $abkCount = $stats['abk_trained_count'];
            $abkFormatted = $abkCount > 0 ? "{$abkCount} ABK" : '0 ABK';
            $danaFormatted = SiteStatsService::formatRupiah($stats['dana_terapi']);

            $impactMetrics = collect([
                (object) [
                    'value' => $barangFormatted,
                    'label' => 'Barang layak pakai tersalurkan',
                    'description' => 'Barang donasi terkurasi yang diterima pemilik baru dan disalurkan secara langsung.',
                ],
                (object) [
                    'value' => $abkFormatted,
                    'label' => 'ABK terlatih mandiri',
                    'description' => 'Anak berkebutuhan khusus yang mengikuti kelas vokasi dan keterampilan mandiri.',
                ],
                (object) [
                    'value' => $danaFormatted,
                    'label' => 'Dana terapi terkumpul',
                    'description' => 'Total dana dari penjualan kios yang dialokasikan untuk operasional ruang terapi gratis.',
                ],
            ]);
        }

        $financialData = $this->financialReportService->getReportData($request->query('periode'));

        $latestMetricDate = ImpactMetric::max('updated_at');
        $latestPurchaseDate = Purchase::whereIn('status', ['paid', 'completed'])->max('updated_at');
        $latestExpenseDate = Expense::max('updated_at');
        $latestDonationDate = Donation::where('status', 'received')->max('updated_at');

        $latestDate = collect([$latestMetricDate, $latestPurchaseDate, $latestExpenseDate, $latestDonationDate])
            ->filter()
            ->max();

        $lastUpdated = $latestDate
            ? Carbon::parse($latestDate)->translatedFormat('d F Y')
            : Carbon::now()->translatedFormat('d F Y');

        return view('user.pages.dampak', compact(
            'summaryCards',
            'impactMetrics',
            'financialData',
            'lastUpdated'
        ));
    }

    /**
     * Display the public Unboxing Gallery page.
     */
    public function unboxing(): View
    {
        $unboxingVideos = Unboxing::visible()
            ->orderByDesc('recorded_at')
            ->orderByDesc('id')
            ->get();

        return view('user.pages.unboxing', compact('unboxingVideos'));
    }
}
