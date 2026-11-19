<?php

namespace App\Providers;

use App\Http\Services\FinancialReportService;
use App\Models\Category;
use App\Models\Expense;
use App\Models\Purchase;
use App\Models\SiteSetting;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by(Str::transliterate(
                Str::lower((string) $request->input('email')).'|'.$request->ip()
            ));
        });

        Purchase::saved(fn () => FinancialReportService::clearCache());
        Purchase::deleted(fn () => FinancialReportService::clearCache());
        Expense::saved(fn () => FinancialReportService::clearCache());
        Expense::deleted(fn () => FinancialReportService::clearCache());
        View::composer(['user.shared.Layout', 'admin.shared.Layout'], function ($view) {
            $view->with('siteSettings', SiteSetting::getPublicSettings());
            try {
                $donationCategories = Category::where('type', 'donation')->orWhere('is_active', true)->get();
            } catch (\Throwable) {
                $donationCategories = collect();
            }
            $view->with('donationCategories', $donationCategories);
        });
    }
}
