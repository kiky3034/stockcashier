<?php

namespace App\Providers;

use App\Models\Purchase;
use App\Models\Sale;
use App\Models\SaleRefund;
use App\Models\Stock;
use App\Observers\DashboardCacheObserver;
use Illuminate\Support\ServiceProvider;

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
        // Auto-invalidate dashboard caches when transactional models change
        Sale::observe(DashboardCacheObserver::class);
        Purchase::observe(DashboardCacheObserver::class);
        SaleRefund::observe(DashboardCacheObserver::class);
        Stock::observe(DashboardCacheObserver::class);
    }
}
