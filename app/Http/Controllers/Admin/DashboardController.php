<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\SaleRefund;
use App\Models\Stock;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $from = now()->startOfDay();
        $to = now()->endOfDay();

        $totalProducts = Product::query()->count();

        $activeProducts = Product::query()
            ->where('is_active', true)
            ->count();

        $lowStockCount = Stock::query()
            ->join('products', 'stocks.product_id', '=', 'products.id')
            ->where('products.is_active', true)
            ->where('products.track_stock', true)
            ->whereColumn('stocks.quantity', '<=', 'products.stock_alert_level')
            ->count();

        $inventoryValue = Stock::query()
            ->join('products', 'stocks.product_id', '=', 'products.id')
            ->where('products.track_stock', true)
            ->sum(DB::raw('stocks.quantity * products.cost_price'));

        $grossSalesToday = Sale::query()
            ->where('status', '!=', 'voided')
            ->whereBetween('sold_at', [$from, $to])
            ->sum('total_amount');

        $refundToday = SaleRefund::query()
            ->where('status', 'completed')
            ->whereBetween('refunded_at', [$from, $to])
            ->sum('total_amount');

        $netSalesToday = (float) $grossSalesToday - (float) $refundToday;

        $transactionsToday = Sale::query()
            ->where('status', '!=', 'voided')
            ->whereBetween('sold_at', [$from, $to])
            ->count();

        $purchasesToday = Purchase::query()
            ->where('status', 'completed')
            ->whereBetween('purchased_at', [$from, $to])
            ->sum('total_amount');

        $recentSales = Sale::query()
            ->with(['cashier', 'warehouse'])
            ->latest('sold_at')
            ->take(5)
            ->get();

        $recentStockMovements = StockMovement::query()
            ->with(['product', 'warehouse', 'user'])
            ->latest()
            ->take(8)
            ->get();

        $latestActivities = ActivityLog::query()
            ->with('user')
            ->latest()
            ->take(8)
            ->get();

        $topProductsToday = DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->where('sales.status', '!=', 'voided')
            ->whereBetween('sales.sold_at', [$from, $to])
            ->selectRaw('
                sale_items.product_id,
                sale_items.product_name,
                sale_items.sku,
                SUM(sale_items.quantity) as total_quantity,
                SUM(sale_items.subtotal) as total_sales
            ')
            ->groupBy('sale_items.product_id', 'sale_items.product_name', 'sale_items.sku')
            ->orderByDesc('total_quantity')
            ->limit(5)
            ->get();

        return view('pages.admin.dashboard', [
            'totalProducts' => $totalProducts,
            'activeProducts' => $activeProducts,
            'lowStockCount' => $lowStockCount,
            'inventoryValue' => $inventoryValue,
            'netSalesToday' => $netSalesToday,
            'transactionsToday' => $transactionsToday,
            'purchasesToday' => $purchasesToday,
            'recentSales' => $recentSales,
            'recentStockMovements' => $recentStockMovements,
            'latestActivities' => $latestActivities,
            'topProductsToday' => $topProductsToday,
        ]);
    }
}