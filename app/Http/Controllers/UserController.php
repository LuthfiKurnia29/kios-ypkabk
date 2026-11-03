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
}
