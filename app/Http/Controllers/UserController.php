<?php

namespace App\Http\Controllers;

use App\Http\Services\ProductService;
use App\Models\Category;
use App\Models\Donation;
use App\Models\ImpactMetric;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function __construct(
        protected ProductService $productService,
    ) {}

    /**
     * Display the user landing page (FR-01).
     */
    public function index(): View
    {
        $featuredProducts = $this->productService->getFeaturedProducts(4);
        $impactMetrics = ImpactMetric::where('is_active', true)->orderBy('order')->get();
        $reviews = Review::active()->get();
        $donationCategories = Category::where('is_active', true)->get();

        return view('user.pages.home', compact(
            'featuredProducts',
            'impactMetrics',
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
     * Display the public Unboxing Gallery page.
     */
    public function unboxing(): View
    {
        // TODO(backend): Hubungkan ke model Unboxing::where('is_visible', true)->latest('recorded_at')->get() setelah migrasi & model dibuat.
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

        return view('user.pages.unboxing', compact('unboxingVideos'));
    }
}
