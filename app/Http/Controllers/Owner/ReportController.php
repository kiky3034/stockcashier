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
use App\Models\Supplier;
use App\Models\Warehouse;
use Symfony\Component\HttpFoundation\StreamedResponse;

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