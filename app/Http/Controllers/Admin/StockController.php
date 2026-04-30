<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StockController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $warehouseId = $request->query('warehouse_id');

        $stocks = Stock::query()
            ->with(['product.category', 'product.unit', 'warehouse'])
            ->when($search, function ($query, $search) {
                $query->whereHas('product', function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%")
                        ->orWhere('barcode', 'like', "%{$search}%");
                });
            })
            ->when($warehouseId, function ($query, $warehouseId) {
                $query->where('warehouse_id', $warehouseId);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('pages.admin.stocks.index', [
            'stocks' => $stocks,
            'warehouses' => Warehouse::where('is_active', true)->orderBy('name')->get(),
            'search' => $search,
            'warehouseId' => $warehouseId,
        ]);
    }
}