<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Stock;
use App\Models\Supplier;
use App\Models\Unit;
use App\Models\Warehouse;
use App\Models\StockMovement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use App\Services\ActivityLogService;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->query('search');

        $products = Product::query()
            ->with(['category', 'unit', 'supplier', 'stocks'])
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%")
                        ->orWhere('barcode', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('pages.admin.products.index', [
            'products' => $products,
            'search' => $search,
        ]);
    }

    public function create(): View
    {
        return view('pages.admin.products.create', [
            'categories' => Category::where('is_active', true)->orderBy('name')->get(),
            'units' => Unit::where('is_active', true)->orderBy('name')->get(),
            'suppliers' => Supplier::where('is_active', true)->orderBy('name')->get(),
            'warehouses' => Warehouse::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request, ActivityLogService $activityLog): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => ['nullable', 'exists:categories,id'],
            'unit_id' => ['nullable', 'exists:units,id'],
            'supplier_id' => ['nullable', 'exists:suppliers,id'],

            'name' => ['required', 'string', 'max:255'],
            'sku' => ['required', 'string', 'max:255', 'unique:products,sku'],
            'barcode' => ['nullable', 'string', 'max:255', 'unique:products,barcode'],

            'cost_price' => ['required', 'numeric', 'min:0'],
            'selling_price' => ['required', 'numeric', 'min:0'],
            'stock_alert_level' => ['nullable', 'numeric', 'min:0'],

            'track_stock' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'description' => ['nullable', 'string'],

            'stocks' => ['nullable', 'array'],
            'stocks.*' => ['nullable', 'numeric', 'min:0'],
        ]);

        DB::transaction(function () use ($request, $validated, $activityLog) {
            $trackStock = $request->boolean('track_stock');

            $product = Product::create([
                'category_id' => $validated['category_id'] ?? null,
                'unit_id' => $validated['unit_id'] ?? null,
                'supplier_id' => $validated['supplier_id'] ?? null,
                'name' => $validated['name'],
                'slug' => $this->makeUniqueSlug($validated['name']),
                'sku' => strtoupper($validated['sku']),
                'barcode' => $validated['barcode'] ?? null,
                'cost_price' => $validated['cost_price'],
                'selling_price' => $validated['selling_price'],
                'stock_alert_level' => $validated['stock_alert_level'] ?? 0,
                'track_stock' => $trackStock,
                'is_active' => $request->boolean('is_active'),
                'description' => $validated['description'] ?? null,
            ]);

            $warehouses = Warehouse::where('is_active', true)->get();
            $stockInputs = $validated['stocks'] ?? [];

            foreach ($warehouses as $warehouse) {
                $quantity = $trackStock ? (float) ($stockInputs[$warehouse->id] ?? 0) : 0;

                Stock::create([
                    'product_id' => $product->id,
                    'warehouse_id' => $warehouse->id,
                    'quantity' => $quantity,
                ]);

                if ($trackStock && $quantity > 0) {
                    StockMovement::create([
                        'product_id' => $product->id,
                        'warehouse_id' => $warehouse->id,
                        'user_id' => $request->user()?->id,
                        'type' => 'initial',
                        'quantity_before' => 0,
                        'quantity_change' => $quantity,
                        'quantity_after' => $quantity,
                        'notes' => 'Initial stock when product created.',
                    ]);
                }
            }

            $activityLog->log(
                event: 'product_created',
                description: 'Product dibuat: ' . $product->name,
                subject: $product,
                properties: [
                    'sku' => $product->sku,
                    'barcode' => $product->barcode,
                    'cost_price' => $product->cost_price,
                    'selling_price' => $product->selling_price,
                    'track_stock' => $product->track_stock,
                    'initial_stocks' => $stockInputs,
                ],
                user: $request->user(),
            );
        });

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product berhasil ditambahkan.');
    }

    public function edit(Product $product): View
    {
        $product->load(['stocks']);

        return view('pages.admin.products.edit', [
            'product' => $product,
            'categories' => Category::where('is_active', true)->orderBy('name')->get(),
            'units' => Unit::where('is_active', true)->orderBy('name')->get(),
            'suppliers' => Supplier::where('is_active', true)->orderBy('name')->get(),
            'warehouses' => Warehouse::where('is_active', true)->orderBy('name')->get(),
            'stockQuantities' => $product->stocks->pluck('quantity', 'warehouse_id'),
        ]);
    }

    public function update(Request $request, Product $product, ActivityLogService $activityLog): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => ['nullable', 'exists:categories,id'],
            'unit_id' => ['nullable', 'exists:units,id'],
            'supplier_id' => ['nullable', 'exists:suppliers,id'],

            'name' => ['required', 'string', 'max:255'],
            'sku' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products', 'sku')->ignore($product->id),
            ],
            'barcode' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('products', 'barcode')->ignore($product->id),
            ],

            'cost_price' => ['required', 'numeric', 'min:0'],
            'selling_price' => ['required', 'numeric', 'min:0'],
            'stock_alert_level' => ['nullable', 'numeric', 'min:0'],

            'track_stock' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'description' => ['nullable', 'string'],

            'stocks' => ['nullable', 'array'],
            'stocks.*' => ['nullable', 'numeric', 'min:0'],
        ]);

        $oldData = [
            'name' => $product->name,
            'sku' => $product->sku,
            'barcode' => $product->barcode,
            'cost_price' => $product->cost_price,
            'selling_price' => $product->selling_price,
            'stock_alert_level' => $product->stock_alert_level,
            'track_stock' => $product->track_stock,
            'is_active' => $product->is_active,
        ];

        DB::transaction(function () use ($request, $validated, $product, $oldData, $activityLog) {
            $trackStock = $request->boolean('track_stock');

            $product->update([
                'category_id' => $validated['category_id'] ?? null,
                'unit_id' => $validated['unit_id'] ?? null,
                'supplier_id' => $validated['supplier_id'] ?? null,
                'name' => $validated['name'],
                'slug' => $this->makeUniqueSlug($validated['name'], $product->id),
                'sku' => strtoupper($validated['sku']),
                'barcode' => $validated['barcode'] ?? null,
                'cost_price' => $validated['cost_price'],
                'selling_price' => $validated['selling_price'],
                'stock_alert_level' => $validated['stock_alert_level'] ?? 0,
                'track_stock' => $trackStock,
                'is_active' => $request->boolean('is_active'),
                'description' => $validated['description'] ?? null,
            ]);

            $warehouses = Warehouse::where('is_active', true)->get();
            $stockInputs = $validated['stocks'] ?? [];

            foreach ($warehouses as $warehouse) {
                Stock::updateOrCreate(
                    [
                        'product_id' => $product->id,
                        'warehouse_id' => $warehouse->id,
                    ],
                    [
                        'quantity' => $trackStock ? ($stockInputs[$warehouse->id] ?? 0) : 0,
                    ]
                );
            }

            $activityLog->log(
                event: 'product_updated',
                description: 'Product diperbarui: ' . $product->name,
                subject: $product,
                properties: [
                    'old' => $oldData,
                    'new' => [
                        'name' => $product->name,
                        'sku' => $product->sku,
                        'barcode' => $product->barcode,
                        'cost_price' => $product->cost_price,
                        'selling_price' => $product->selling_price,
                        'stock_alert_level' => $product->stock_alert_level,
                        'track_stock' => $product->track_stock,
                        'is_active' => $product->is_active,
                    ],
                    'stocks' => $stockInputs,
                ],
                user: $request->user(),
            );
        });

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product berhasil diperbarui.');
    }

    public function destroy(Request $request, Product $product, ActivityLogService $activityLog): RedirectResponse
    {
        if ($product->stocks()->where('quantity', '>', 0)->exists()) {
            return redirect()
                ->route('admin.products.index')
                ->with('error', 'Product tidak bisa dihapus karena masih memiliki stok.');
        }

        if ($product->stockMovements()->exists()) {
            return redirect()
                ->route('admin.products.index')
                ->with('error', 'Product tidak bisa dihapus karena sudah memiliki riwayat stock movement.');
        }

        if ($product->saleItems()->exists() || $product->purchaseItems()->exists()) {
            return redirect()
                ->route('admin.products.index')
                ->with('error', 'Product tidak bisa dihapus karena sudah digunakan dalam transaksi.');
        }

        $deletedProduct = [
            'id' => $product->id,
            'name' => $product->name,
            'sku' => $product->sku,
            'barcode' => $product->barcode,
            'cost_price' => $product->cost_price,
            'selling_price' => $product->selling_price,
        ];

        $product->delete();

        $activityLog->log(
            event: 'product_deleted',
            description: 'Product dihapus: ' . $deletedProduct['name'],
            subject: null,
            properties: [
                'deleted_product' => $deletedProduct,
            ],
            user: $request->user(),
        );

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product berhasil dihapus.');
    }

    private function makeUniqueSlug(string $name, ?int $ignoreProductId = null): string
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $counter = 1;

        while (
            Product::query()
                ->where('slug', $slug)
                ->when($ignoreProductId, fn ($query) => $query->where('id', '!=', $ignoreProductId))
                ->exists()
        ) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}