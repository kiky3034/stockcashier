<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\SaleRefund;
use App\Models\Stock;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use App\Models\Supplier;
use App\Models\Warehouse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function dashboard(): View
    {
        $from = now()->startOfDay();
        $to = now()->endOfDay();

        // Cache today's aggregate data for 60 seconds
        $todayKey = 'owner_dashboard_today_' . now()->format('Ymd');

        $todayData = Cache::remember($todayKey, 60, function () use ($from, $to) {
            return [
                'netSalesToday' => $this->calculateNetSales($from, $to),
                'grossProfitToday' => $this->calculateGrossProfit($from, $to),
                'transactionCountToday' => Sale::query()
                    ->where('status', '!=', 'voided')
                    ->whereBetween('sold_at', [$from, $to])
                    ->count(),
                'purchaseTotalToday' => Purchase::query()
                    ->where('status', 'completed')
                    ->whereBetween('purchased_at', [$from, $to])
                    ->sum('total_amount'),
            ];
        });

        // Low stocks — use JOIN instead of whereHas + collection filter (N+1 fix)
        $lowStocks = Stock::query()
            ->select('stocks.*')
            ->join('products', 'stocks.product_id', '=', 'products.id')
            ->with(['product.unit', 'warehouse'])
            ->where('products.is_active', true)
            ->where('products.track_stock', true)
            ->whereColumn('stocks.quantity', '<=', 'products.stock_alert_level')
            ->limit(10)
            ->get();

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

        // Daily performance — batch query instead of 14 individual queries
        $dailyPerformance = $this->calculateDailyPerformanceBatch();

        $maxDailySales = max((float) $dailyPerformance->max('sales'), 1);
        $maxDailyProfit = max((float) $dailyPerformance->max('profit'), 1);

        return view('pages.owner.dashboard', [
            'netSalesToday' => $todayData['netSalesToday'],
            'grossProfitToday' => $todayData['grossProfitToday'],
            'transactionCountToday' => $todayData['transactionCountToday'],
            'purchaseTotalToday' => $todayData['purchaseTotalToday'],
            'lowStockCount' => $lowStockCount,
            'lowStocks' => $lowStocks,
            'recentSales' => $recentSales,
            'topProducts' => $topProducts,
            'dailyPerformance' => $dailyPerformance,
            'maxDailySales' => $maxDailySales,
            'maxDailyProfit' => $maxDailyProfit,
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

    /**
     * Calculate daily sales and profit for the last 7 days using batch queries.
     * Reduces from 14 individual queries (7 days × 2 calculations) to just 2 queries.
     */
    private function calculateDailyPerformanceBatch(): \Illuminate\Support\Collection
    {
        $rangeFrom = now()->subDays(6)->startOfDay();
        $rangeTo = now()->endOfDay();

        // Batch query 1: daily net sales (gross sales - refunds)
        $dailySales = DB::table('sales')
            ->where('status', '!=', 'voided')
            ->whereBetween('sold_at', [$rangeFrom, $rangeTo])
            ->selectRaw('DATE(sold_at) as sale_date, COALESCE(SUM(total_amount), 0) as gross_sales')
            ->groupBy('sale_date')
            ->pluck('gross_sales', 'sale_date');

        $dailyRefunds = DB::table('sale_refunds')
            ->where('status', 'completed')
            ->whereBetween('refunded_at', [$rangeFrom, $rangeTo])
            ->selectRaw('DATE(refunded_at) as refund_date, COALESCE(SUM(total_amount), 0) as total_refunds')
            ->groupBy('refund_date')
            ->pluck('total_refunds', 'refund_date');

        // Batch query 2: daily gross profit
        $dailySalesProfit = DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->where('sales.status', '!=', 'voided')
            ->whereBetween('sales.sold_at', [$rangeFrom, $rangeTo])
            ->selectRaw('DATE(sales.sold_at) as sale_date, COALESCE(SUM((sale_items.unit_price - sale_items.cost_price) * sale_items.quantity), 0) as profit')
            ->groupBy('sale_date')
            ->pluck('profit', 'sale_date');

        $dailyRefundProfit = DB::table('sale_refund_items')
            ->join('sale_refunds', 'sale_refund_items.sale_refund_id', '=', 'sale_refunds.id')
            ->join('sale_items', 'sale_refund_items.sale_item_id', '=', 'sale_items.id')
            ->where('sale_refunds.status', 'completed')
            ->whereBetween('sale_refunds.refunded_at', [$rangeFrom, $rangeTo])
            ->selectRaw('DATE(sale_refunds.refunded_at) as refund_date, COALESCE(SUM((sale_refund_items.unit_price - sale_items.cost_price) * sale_refund_items.quantity), 0) as profit')
            ->groupBy('refund_date')
            ->pluck('profit', 'refund_date');

        // Assemble results for each of the 7 days
        return collect(range(6, 0))->map(function ($daysAgo) use ($dailySales, $dailyRefunds, $dailySalesProfit, $dailyRefundProfit) {
            $date = now()->subDays($daysAgo);
            $dateKey = $date->format('Y-m-d');

            $grossSales = (float) ($dailySales[$dateKey] ?? 0);
            $refunds = (float) ($dailyRefunds[$dateKey] ?? 0);
            $salesProfit = (float) ($dailySalesProfit[$dateKey] ?? 0);
            $refundProfit = (float) ($dailyRefundProfit[$dateKey] ?? 0);

            return [
                'label' => $date->format('d M'),
                'sales' => $grossSales - $refunds,
                'profit' => $salesProfit - $refundProfit,
            ];
        });
    }

    public function stock(Request $request): View
    {
        $search = $request->query('search');
        $warehouseId = $request->query('warehouse_id');
        $status = $request->query('status');

        $stocksQuery = Stock::query()
            ->select('stocks.*')
            ->join('products', 'stocks.product_id', '=', 'products.id')
            ->with(['product.category', 'product.unit', 'warehouse'])
            ->where('products.is_active', true)
            ->where('products.track_stock', true)
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('products.name', 'like', "%{$search}%")
                        ->orWhere('products.sku', 'like', "%{$search}%")
                        ->orWhere('products.barcode', 'like', "%{$search}%");
                });
            })
            ->when($warehouseId, function ($query, $warehouseId) {
                $query->where('stocks.warehouse_id', $warehouseId);
            })
            ->when($status === 'low', function ($query) {
                $query->whereColumn('stocks.quantity', '<=', 'products.stock_alert_level');
            })
            ->when($status === 'safe', function ($query) {
                $query->whereColumn('stocks.quantity', '>', 'products.stock_alert_level');
            });

        $totalStockValue = (clone $stocksQuery)
            ->sum(DB::raw('stocks.quantity * products.cost_price'));

        $totalQuantity = (clone $stocksQuery)
            ->sum('stocks.quantity');

        $lowStockCount = (clone $stocksQuery)
            ->whereColumn('stocks.quantity', '<=', 'products.stock_alert_level')
            ->count();

        $stocks = $stocksQuery
            ->orderBy('products.name')
            ->paginate(15)
            ->withQueryString();

        return view('pages.owner.reports.stock', [
            'stocks' => $stocks,
            'warehouses' => Warehouse::where('is_active', true)->orderBy('name')->get(),
            'search' => $search,
            'warehouseId' => $warehouseId,
            'status' => $status,
            'totalStockValue' => $totalStockValue,
            'totalQuantity' => $totalQuantity,
            'lowStockCount' => $lowStockCount,
        ]);
    }

    public function purchases(Request $request): View
    {
        [$from, $to] = $this->resolveDateRange($request);

        $supplierId = $request->query('supplier_id');
        $warehouseId = $request->query('warehouse_id');
        $search = $request->query('search');

        $purchasesQuery = Purchase::query()
            ->with(['supplier', 'warehouse', 'user'])
            ->whereBetween('purchased_at', [$from, $to])
            ->when($search, function ($query, $search) {
                $query->where('purchase_number', 'like', "%{$search}%");
            })
            ->when($supplierId, function ($query, $supplierId) {
                $query->where('supplier_id', $supplierId);
            })
            ->when($warehouseId, function ($query, $warehouseId) {
                $query->where('warehouse_id', $warehouseId);
            });

        $totalPurchase = (clone $purchasesQuery)
            ->where('status', 'completed')
            ->sum('total_amount');

        $purchaseCount = (clone $purchasesQuery)
            ->where('status', 'completed')
            ->count();

        $totalItems = DB::table('purchase_items')
            ->join('purchases', 'purchase_items.purchase_id', '=', 'purchases.id')
            ->where('purchases.status', 'completed')
            ->whereBetween('purchases.purchased_at', [$from, $to])
            ->when($supplierId, function ($query, $supplierId) {
                $query->where('purchases.supplier_id', $supplierId);
            })
            ->when($warehouseId, function ($query, $warehouseId) {
                $query->where('purchases.warehouse_id', $warehouseId);
            })
            ->sum('purchase_items.quantity');

        $topPurchasedProducts = DB::table('purchase_items')
            ->join('purchases', 'purchase_items.purchase_id', '=', 'purchases.id')
            ->where('purchases.status', 'completed')
            ->whereBetween('purchases.purchased_at', [$from, $to])
            ->when($supplierId, function ($query, $supplierId) {
                $query->where('purchases.supplier_id', $supplierId);
            })
            ->when($warehouseId, function ($query, $warehouseId) {
                $query->where('purchases.warehouse_id', $warehouseId);
            })
            ->selectRaw('
                purchase_items.product_id,
                purchase_items.product_name,
                purchase_items.sku,
                SUM(purchase_items.quantity) as total_quantity,
                SUM(purchase_items.subtotal) as total_amount
            ')
            ->groupBy('purchase_items.product_id', 'purchase_items.product_name', 'purchase_items.sku')
            ->orderByDesc('total_quantity')
            ->limit(10)
            ->get();

        $purchases = $purchasesQuery
            ->latest('purchased_at')
            ->paginate(15)
            ->withQueryString();

        return view('pages.owner.reports.purchases', [
            'purchases' => $purchases,
            'suppliers' => Supplier::where('is_active', true)->orderBy('name')->get(),
            'warehouses' => Warehouse::where('is_active', true)->orderBy('name')->get(),
            'from' => $from,
            'to' => $to,
            'supplierId' => $supplierId,
            'warehouseId' => $warehouseId,
            'search' => $search,
            'totalPurchase' => $totalPurchase,
            'purchaseCount' => $purchaseCount,
            'totalItems' => $totalItems,
            'topPurchasedProducts' => $topPurchasedProducts,
        ]);
    }

    private function downloadCsv(string $filename, array $headers, iterable $rows): StreamedResponse
    {
        return response()->streamDownload(function () use ($headers, $rows) {
            $file = fopen('php://output', 'w');

            fputcsv($file, $headers);

            foreach ($rows as $row) {
                fputcsv($file, $row);
            }

            fclose($file);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function exportSales(Request $request): StreamedResponse
    {
        [$from, $to] = $this->resolveDateRange($request);

        $sales = Sale::query()
            ->with(['cashier', 'warehouse'])
            ->whereBetween('sold_at', [$from, $to])
            ->latest('sold_at')
            ->get();

        $rows = $sales->map(function ($sale) {
            return [
                $sale->invoice_number,
                $sale->sold_at?->format('Y-m-d H:i:s'),
                $sale->cashier->name,
                $sale->warehouse->name,
                $sale->status,
                $sale->subtotal,
                $sale->discount_amount,
                $sale->tax_amount,
                $sale->total_amount,
                $sale->paid_amount,
                $sale->change_amount,
            ];
        });

        return $this->downloadCsv(
            'sales-report-' . now()->format('YmdHis') . '.csv',
            [
                'Invoice',
                'Date',
                'Cashier',
                'Warehouse',
                'Status',
                'Subtotal',
                'Discount',
                'Tax',
                'Total',
                'Paid',
                'Change',
            ],
            $rows
        );
    }

    public function exportProfit(Request $request): StreamedResponse
    {
        [$from, $to] = $this->resolveDateRange($request);

        $rows = DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->where('sales.status', '!=', 'voided')
            ->whereBetween('sales.sold_at', [$from, $to])
            ->selectRaw('
                sale_items.product_name,
                sale_items.sku,
                SUM(sale_items.quantity) as total_quantity,
                SUM(sale_items.subtotal) as total_sales,
                SUM(sale_items.cost_price * sale_items.quantity) as total_cost,
                SUM((sale_items.unit_price - sale_items.cost_price) * sale_items.quantity) as gross_profit
            ')
            ->groupBy('sale_items.product_name', 'sale_items.sku')
            ->orderByDesc('gross_profit')
            ->get()
            ->map(function ($item) {
                return [
                    $item->product_name,
                    $item->sku,
                    $item->total_quantity,
                    $item->total_sales,
                    $item->total_cost,
                    $item->gross_profit,
                ];
            });

        return $this->downloadCsv(
            'profit-report-' . now()->format('YmdHis') . '.csv',
            [
                'Product',
                'SKU',
                'Qty Sold',
                'Sales',
                'Cost',
                'Gross Profit',
            ],
            $rows
        );
    }

    public function exportStock(Request $request): StreamedResponse
    {
        $search = $request->query('search');
        $warehouseId = $request->query('warehouse_id');
        $status = $request->query('status');

        $stocks = Stock::query()
            ->select('stocks.*')
            ->join('products', 'stocks.product_id', '=', 'products.id')
            ->with(['product.category', 'product.unit', 'warehouse'])
            ->where('products.is_active', true)
            ->where('products.track_stock', true)
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('products.name', 'like', "%{$search}%")
                        ->orWhere('products.sku', 'like', "%{$search}%")
                        ->orWhere('products.barcode', 'like', "%{$search}%");
                });
            })
            ->when($warehouseId, function ($query, $warehouseId) {
                $query->where('stocks.warehouse_id', $warehouseId);
            })
            ->when($status === 'low', function ($query) {
                $query->whereColumn('stocks.quantity', '<=', 'products.stock_alert_level');
            })
            ->when($status === 'safe', function ($query) {
                $query->whereColumn('stocks.quantity', '>', 'products.stock_alert_level');
            })
            ->orderBy('products.name')
            ->get();

        $rows = $stocks->map(function ($stock) {
            return [
                $stock->product->name,
                $stock->product->sku,
                $stock->product->category?->name,
                $stock->warehouse->name,
                $stock->quantity,
                $stock->product->unit?->abbreviation,
                $stock->product->stock_alert_level,
                $stock->quantity <= $stock->product->stock_alert_level ? 'Low Stock' : 'Safe',
                $stock->product->cost_price,
                $stock->quantity * $stock->product->cost_price,
            ];
        });

        return $this->downloadCsv(
            'stock-report-' . now()->format('YmdHis') . '.csv',
            [
                'Product',
                'SKU',
                'Category',
                'Warehouse',
                'Quantity',
                'Unit',
                'Alert Level',
                'Status',
                'Cost Price',
                'Stock Value',
            ],
            $rows
        );
    }

    public function exportPurchases(Request $request): StreamedResponse
    {
        [$from, $to] = $this->resolveDateRange($request);

        $supplierId = $request->query('supplier_id');
        $warehouseId = $request->query('warehouse_id');
        $search = $request->query('search');

        $purchases = Purchase::query()
            ->with(['supplier', 'warehouse', 'user'])
            ->whereBetween('purchased_at', [$from, $to])
            ->when($search, function ($query, $search) {
                $query->where('purchase_number', 'like', "%{$search}%");
            })
            ->when($supplierId, function ($query, $supplierId) {
                $query->where('supplier_id', $supplierId);
            })
            ->when($warehouseId, function ($query, $warehouseId) {
                $query->where('warehouse_id', $warehouseId);
            })
            ->latest('purchased_at')
            ->get();

        $rows = $purchases->map(function ($purchase) {
            return [
                $purchase->purchase_number,
                $purchase->purchased_at?->format('Y-m-d H:i:s'),
                $purchase->supplier->name,
                $purchase->warehouse->name,
                $purchase->user->name,
                $purchase->status,
                $purchase->subtotal,
                $purchase->discount_amount,
                $purchase->tax_amount,
                $purchase->total_amount,
            ];
        });

        return $this->downloadCsv(
            'purchase-report-' . now()->format('YmdHis') . '.csv',
            [
                'Purchase Number',
                'Date',
                'Supplier',
                'Warehouse',
                'User',
                'Status',
                'Subtotal',
                'Discount',
                'Tax',
                'Total',
            ],
            $rows
        );
    }
}