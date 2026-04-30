<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\SaleRefund;
use App\Models\Stock;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function dashboard(): View
    {
        $from = now()->startOfDay();
        $to = now()->endOfDay();

        $netSalesToday = $this->calculateNetSales($from, $to);
        $grossProfitToday = $this->calculateGrossProfit($from, $to);

        $transactionCountToday = Sale::query()
            ->where('status', '!=', 'voided')
            ->whereBetween('sold_at', [$from, $to])
            ->count();

        $purchaseTotalToday = Purchase::query()
            ->where('status', 'completed')
            ->whereBetween('purchased_at', [$from, $to])
            ->sum('total_amount');

        $lowStocks = Stock::query()
            ->with(['product.unit', 'warehouse'])
            ->whereHas('product', function ($query) {
                $query->where('is_active', true)
                    ->where('track_stock', true);
            })
            ->get()
            ->filter(function ($stock) {
                return (float) $stock->quantity <= (float) $stock->product->stock_alert_level;
            })
            ->take(10);

        $lowStockCount = $lowStocks->count();

        $recentSales = Sale::query()
            ->with(['cashier', 'warehouse'])
            ->latest('sold_at')
            ->take(5)
            ->get();

        $topProducts = DB::table('sale_items')
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

        return view('pages.owner.dashboard', [
            'netSalesToday' => $netSalesToday,
            'grossProfitToday' => $grossProfitToday,
            'transactionCountToday' => $transactionCountToday,
            'purchaseTotalToday' => $purchaseTotalToday,
            'lowStockCount' => $lowStockCount,
            'lowStocks' => $lowStocks,
            'recentSales' => $recentSales,
            'topProducts' => $topProducts,
        ]);
    }

    public function sales(Request $request): View
    {
        [$from, $to] = $this->resolveDateRange($request);

        $sales = Sale::query()
            ->with(['cashier', 'warehouse'])
            ->whereBetween('sold_at', [$from, $to])
            ->latest('sold_at')
            ->paginate(15)
            ->withQueryString();

        $grossSales = Sale::query()
            ->where('status', '!=', 'voided')
            ->whereBetween('sold_at', [$from, $to])
            ->sum('total_amount');

        $refundTotal = SaleRefund::query()
            ->where('status', 'completed')
            ->whereBetween('refunded_at', [$from, $to])
            ->sum('total_amount');

        $voidedSales = Sale::query()
            ->where('status', 'voided')
            ->whereBetween('sold_at', [$from, $to])
            ->sum('total_amount');

        $netSales = $grossSales - $refundTotal;

        $transactionCount = Sale::query()
            ->where('status', '!=', 'voided')
            ->whereBetween('sold_at', [$from, $to])
            ->count();

        return view('pages.owner.reports.sales', [
            'sales' => $sales,
            'from' => $from,
            'to' => $to,
            'grossSales' => $grossSales,
            'refundTotal' => $refundTotal,
            'voidedSales' => $voidedSales,
            'netSales' => $netSales,
            'transactionCount' => $transactionCount,
        ]);
    }

    public function profit(Request $request): View
    {
        [$from, $to] = $this->resolveDateRange($request);

        $grossSales = Sale::query()
            ->where('status', '!=', 'voided')
            ->whereBetween('sold_at', [$from, $to])
            ->sum('total_amount');

        $refundTotal = SaleRefund::query()
            ->where('status', 'completed')
            ->whereBetween('refunded_at', [$from, $to])
            ->sum('total_amount');

        $netSales = $grossSales - $refundTotal;

        $grossProfit = $this->calculateGrossProfit($from, $to);

        $costOfGoodsSold = $this->calculateCostOfGoodsSold($from, $to);

        $topProfitProducts = DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->where('sales.status', '!=', 'voided')
            ->whereBetween('sales.sold_at', [$from, $to])
            ->selectRaw('
                sale_items.product_id,
                sale_items.product_name,
                sale_items.sku,
                SUM(sale_items.quantity) as total_quantity,
                SUM(sale_items.subtotal) as total_sales,
                SUM(sale_items.cost_price * sale_items.quantity) as total_cost,
                SUM((sale_items.unit_price - sale_items.cost_price) * sale_items.quantity) as gross_profit
            ')
            ->groupBy('sale_items.product_id', 'sale_items.product_name', 'sale_items.sku')
            ->orderByDesc('gross_profit')
            ->limit(10)
            ->get();

        return view('pages.owner.reports.profit', [
            'from' => $from,
            'to' => $to,
            'grossSales' => $grossSales,
            'refundTotal' => $refundTotal,
            'netSales' => $netSales,
            'costOfGoodsSold' => $costOfGoodsSold,
            'grossProfit' => $grossProfit,
            'topProfitProducts' => $topProfitProducts,
        ]);
    }

    private function resolveDateRange(Request $request): array
    {
        $from = $request->query('from')
            ? Carbon::parse($request->query('from'))->startOfDay()
            : now()->startOfMonth();

        $to = $request->query('to')
            ? Carbon::parse($request->query('to'))->endOfDay()
            : now()->endOfDay();

        return [$from, $to];
    }

    private function calculateNetSales(Carbon $from, Carbon $to): float
    {
        $grossSales = Sale::query()
            ->where('status', '!=', 'voided')
            ->whereBetween('sold_at', [$from, $to])
            ->sum('total_amount');

        $refundTotal = SaleRefund::query()
            ->where('status', 'completed')
            ->whereBetween('refunded_at', [$from, $to])
            ->sum('total_amount');

        return (float) $grossSales - (float) $refundTotal;
    }

    private function calculateGrossProfit(Carbon $from, Carbon $to): float
    {
        $salesProfit = DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->where('sales.status', '!=', 'voided')
            ->whereBetween('sales.sold_at', [$from, $to])
            ->selectRaw('
                COALESCE(SUM((sale_items.unit_price - sale_items.cost_price) * sale_items.quantity), 0) as total
            ')
            ->value('total');

        $refundProfit = DB::table('sale_refund_items')
            ->join('sale_refunds', 'sale_refund_items.sale_refund_id', '=', 'sale_refunds.id')
            ->join('sale_items', 'sale_refund_items.sale_item_id', '=', 'sale_items.id')
            ->where('sale_refunds.status', 'completed')
            ->whereBetween('sale_refunds.refunded_at', [$from, $to])
            ->selectRaw('
                COALESCE(SUM((sale_refund_items.unit_price - sale_items.cost_price) * sale_refund_items.quantity), 0) as total
            ')
            ->value('total');

        return (float) $salesProfit - (float) $refundProfit;
    }

    private function calculateCostOfGoodsSold(Carbon $from, Carbon $to): float
    {
        $salesCost = DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->where('sales.status', '!=', 'voided')
            ->whereBetween('sales.sold_at', [$from, $to])
            ->selectRaw('
                COALESCE(SUM(sale_items.cost_price * sale_items.quantity), 0) as total
            ')
            ->value('total');

        $refundCost = DB::table('sale_refund_items')
            ->join('sale_refunds', 'sale_refund_items.sale_refund_id', '=', 'sale_refunds.id')
            ->join('sale_items', 'sale_refund_items.sale_item_id', '=', 'sale_items.id')
            ->where('sale_refunds.status', 'completed')
            ->whereBetween('sale_refunds.refunded_at', [$from, $to])
            ->selectRaw('
                COALESCE(SUM(sale_items.cost_price * sale_refund_items.quantity), 0) as total
            ')
            ->value('total');

        return (float) $salesCost - (float) $refundCost;
    }
}