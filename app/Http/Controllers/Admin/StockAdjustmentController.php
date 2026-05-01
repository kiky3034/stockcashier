<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Warehouse;
use App\Services\StockService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Services\ActivityLogService;

class StockAdjustmentController extends Controller
{
    public function create(): View
    {
        return view('pages.admin.stock-adjustments.create', [
            'products' => Product::where('is_active', true)
                ->where('track_stock', true)
                ->orderBy('name')
                ->get(),
            'warehouses' => Warehouse::where('is_active', true)
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function store(Request $request, StockService $stockService, ActivityLogService $activityLog): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'warehouse_id' => ['required', 'exists:warehouses,id'],
            'direction' => ['required', 'in:in,out'],
            'quantity' => ['required', 'numeric', 'min:0.01'],
            'notes' => ['nullable', 'string'],
        ]);

        $product = Product::findOrFail($validated['product_id']);
        $warehouse = Warehouse::findOrFail($validated['warehouse_id']);

        if ($validated['direction'] === 'in') {
            $movement = $stockService->increase(
                product: $product,
                warehouse: $warehouse,
                quantity: (float) $validated['quantity'],
                type: 'adjustment_in',
                user: $request->user(),
                notes: $validated['notes'] ?? null,
            );
        } else {
            $movement = $stockService->decrease(
                product: $product,
                warehouse: $warehouse,
                quantity: (float) $validated['quantity'],
                type: 'adjustment_out',
                user: $request->user(),
                notes: $validated['notes'] ?? null,
            );
        }

        $activityLog->log(
            event: 'stock_adjusted',
            description: 'Stock adjustment ' . $validated['direction'] . ': ' . $product->name,
            subject: $movement,
            properties: [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'sku' => $product->sku,
                'warehouse_id' => $warehouse->id,
                'warehouse_name' => $warehouse->name,
                'direction' => $validated['direction'],
                'quantity' => $validated['quantity'],
                'quantity_before' => $movement->quantity_before,
                'quantity_change' => $movement->quantity_change,
                'quantity_after' => $movement->quantity_after,
                'notes' => $validated['notes'] ?? null,
            ],
            user: $request->user(),
        );

        return redirect()
            ->route('admin.stock-movements.index')
            ->with('success', 'Stock adjustment berhasil disimpan.');
    }
}