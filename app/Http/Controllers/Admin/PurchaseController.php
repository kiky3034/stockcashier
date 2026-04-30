<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\Warehouse;
use App\Services\PurchaseService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PurchaseController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $supplierId = $request->query('supplier_id');
        $warehouseId = $request->query('warehouse_id');

        $purchases = Purchase::query()
            ->with(['supplier', 'warehouse', 'user'])
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
            ->paginate(10)
            ->withQueryString();

        return view('pages.admin.purchases.index', [
            'purchases' => $purchases,
            'suppliers' => Supplier::where('is_active', true)->orderBy('name')->get(),
            'warehouses' => Warehouse::where('is_active', true)->orderBy('name')->get(),
            'search' => $search,
            'supplierId' => $supplierId,
            'warehouseId' => $warehouseId,
        ]);
    }

    public function create(): View
    {
        return view('pages.admin.purchases.create', [
            'suppliers' => Supplier::where('is_active', true)->orderBy('name')->get(),
            'warehouses' => Warehouse::where('is_active', true)->orderBy('name')->get(),
            'products' => Product::query()
                ->with(['unit', 'category'])
                ->where('is_active', true)
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function store(Request $request, PurchaseService $purchaseService): RedirectResponse
    {
        $validated = $request->validate([
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'warehouse_id' => ['required', 'exists:warehouses,id'],

            'discount_amount' => ['nullable', 'numeric', 'min:0'],
            'tax_amount' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
            'update_cost_price' => ['nullable', 'boolean'],

            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['nullable', 'numeric', 'min:0'],
            'items.*.unit_cost' => ['required', 'numeric', 'min:0'],
        ]);

        $purchase = $purchaseService->createPurchase(
            data: $validated,
            user: $request->user(),
        );

        return redirect()
            ->route('admin.purchases.show', $purchase)
            ->with('success', 'Purchase berhasil disimpan dan stok sudah bertambah.');
    }

    public function show(Purchase $purchase): View
    {
        $purchase->load(['items.product', 'supplier', 'warehouse', 'user']);

        return view('pages.admin.purchases.show', [
            'purchase' => $purchase,
        ]);
    }
}