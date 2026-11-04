<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\Product;
use App\Models\Purchase;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * Display the admin dashboard (NFR-04).
     */
    public function dashboard(): View
    {
        $totalProducts = Product::count();
        $todayPurchases = Purchase::whereDate('created_at', today())->count();
        $totalDonations = Donation::count();
        $monthlyRevenue = Purchase::whereIn('status', ['paid', 'completed'])
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_price');

        $recentPurchases = Purchase::with(['detailPurchases.product', 'user'])
            ->latest()
            ->take(8)
            ->get();

        $recentDonations = Donation::with('category')
            ->latest()
            ->take(8)
            ->get();

        return view('admin.pages.dashboard', compact(
            'totalProducts',
            'todayPurchases',
            'totalDonations',
            'monthlyRevenue',
            'recentPurchases',
            'recentDonations'
        ));
    }
}
