<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSaleRequest;
use App\Models\Product;
use App\Models\Warehouse;
use App\Services\SaleService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\AppSetting;

class PosController extends Controller
{
    public function index(): View
    {
        $products = Product::query()
            ->with(['category', 'unit', 'stocks'])
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $warehouses = Warehouse::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('pages.cashier.pos.index', [
            'products' => $products,
            'posProducts' => $products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'barcode' => $product->barcode,
                    'price' => (float) $product->selling_price,
                    'unit' => $product->unit?->abbreviation,
                    'category' => $product->category?->name,
                    'track_stock' => (bool) $product->track_stock,
                    'image_url' => $product->image_path ? asset('storage/' . $product->image_path) : null,
                    'stocks_by_warehouse' => $product->stocks
                        ->mapWithKeys(function ($stock) {
                            return [
                                $stock->warehouse_id => (float) $stock->quantity,
                            ];
                        })
                        ->all(),
                ];
            })->values()->all(),
            'warehouses' => $warehouses,
        ]);
    }

    public function store(StoreSaleRequest $request, SaleService $saleService): RedirectResponse
    {
        $validated = $request->validated();

        $sale = $saleService->createSale($validated, $request->user());

        $autoPrint = AppSetting::valueFor('receipt_auto_print', 'false') === 'true';

        if ($autoPrint) {
            return redirect()
                ->route('cashier.sales.receipt', $sale)
                ->with('success', 'Transaksi berhasil disimpan.');
        }

        return redirect()
            ->route('cashier.sales.show', $sale)
            ->with('success', 'Transaksi berhasil disimpan.');
    }
}