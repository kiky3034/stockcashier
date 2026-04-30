<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StockMovement;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StockMovementController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $type = $request->query('type');
        $warehouseId = $request->query('warehouse_id');

        $movements = StockMovement::query()
            ->with(['product.unit', 'warehouse', 'user'])
            ->when($search, function ($query, $search) {
                $query->whereHas('product', function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%")
                        ->orWhere('barcode', 'like', "%{$search}%");
                });
            })
            ->when($type, function ($query, $type) {
                $query->where('type', $type);
            })
            ->when($warehouseId, function ($query, $warehouseId) {
                $query->where('warehouse_id', $warehouseId);
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('pages.admin.stock-movements.index', [
            'movements' => $movements,
            'warehouses' => Warehouse::where('is_active', true)->orderBy('name')->get(),
            'types' => StockMovement::query()
                ->select('type')
                ->distinct()
                ->orderBy('type')
                ->pluck('type'),
            'search' => $search,
            'type' => $type,
            'warehouseId' => $warehouseId,
        ]);
    }
}