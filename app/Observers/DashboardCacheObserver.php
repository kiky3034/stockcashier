<?php

namespace App\Observers;

use Illuminate\Support\Facades\Cache;

/**
 * Clears dashboard caches when any transactional model (Sale, Purchase, Refund, Stock) changes.
 *
 * This observer uses a single trait-like approach: attach it to any model whose changes
 * should invalidate the dashboard aggregate caches.
 */
class DashboardCacheObserver
{
    /**
     * Flush dashboard caches on any write event.
     */
    public function created($model): void
    {
        $this->flushDashboardCaches();
    }

    public function updated($model): void
    {
        $this->flushDashboardCaches();
    }

    public function deleted($model): void
    {
        $this->flushDashboardCaches();
    }

    private function flushDashboardCaches(): void
    {
        $todayKey = now()->format('Ymd');

        Cache::forget('admin_dashboard_' . $todayKey);
        Cache::forget('owner_dashboard_today_' . $todayKey);
    }
}
