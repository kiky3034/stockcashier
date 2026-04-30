<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Warehouse;
use App\Services\StockService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

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

    public function store(Request $request, StockService $stockService): RedirectResponse
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
            $stockService->increase(
                product: $product,
                warehouse: $warehouse,
                quantity: (float) $validated['quantity'],
                type: 'adjustment_in',
                user: $request->user(),
                notes: $validated['notes'] ?? null,
            );
        } else {
            $stockService->decrease(
                product: $product,
                warehouse: $warehouse,
                quantity: (float) $validated['quantity'],
                type: 'adjustment_out',
                user: $request->user(),
                notes: $validated['notes'] ?? null,
            );
        }

        return redirect()
            ->route('admin.stock-movements.index')
            ->with('success', 'Stock adjustment berhasil disimpan.');
    }
}