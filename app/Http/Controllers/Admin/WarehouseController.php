<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class WarehouseController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->query('search');

        $warehouses = Warehouse::query()
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('pages.admin.warehouses.index', [
            'warehouses' => $warehouses,
            'search' => $search,
        ]);
    }

    public function create(): View
    {
        return view('pages.admin.warehouses.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:warehouses,name'],
            'code' => ['required', 'string', 'max:50', 'unique:warehouses,code'],
            'address' => ['nullable', 'string'],
            'is_default' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        DB::transaction(function () use ($request, $validated) {
            if ($request->boolean('is_default')) {
                Warehouse::query()->update(['is_default' => false]);
            }

            Warehouse::create([
                'name' => $validated['name'],
                'code' => strtoupper($validated['code']),
                'address' => $validated['address'] ?? null,
                'is_default' => $request->boolean('is_default'),
                'is_active' => $request->boolean('is_active'),
            ]);
        });

        return redirect()
            ->route('admin.warehouses.index')
            ->with('success', 'Warehouse berhasil ditambahkan.');
    }

    public function show(Warehouse $warehouse): View
    {
        return view('pages.admin.warehouses.show', [
            'warehouse' => $warehouse,
        ]);
    }

    public function edit(Warehouse $warehouse): View
    {
        return view('pages.admin.warehouses.edit', [
            'warehouse' => $warehouse,
        ]);
    }

    public function update(Request $request, Warehouse $warehouse): RedirectResponse
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('warehouses', 'name')->ignore($warehouse->id),
            ],
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('warehouses', 'code')->ignore($warehouse->id),
            ],
            'address' => ['nullable', 'string'],
            'is_default' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        DB::transaction(function () use ($request, $validated, $warehouse) {
            if ($request->boolean('is_default')) {
                Warehouse::query()
                    ->where('id', '!=', $warehouse->id)
                    ->update(['is_default' => false]);
            }

            $warehouse->update([
                'name' => $validated['name'],
                'code' => strtoupper($validated['code']),
                'address' => $validated['address'] ?? null,
                'is_default' => $request->boolean('is_default'),
                'is_active' => $request->boolean('is_active'),
            ]);
        });

        return redirect()
            ->route('admin.warehouses.index')
            ->with('success', 'Warehouse berhasil diperbarui.');
    }

    public function destroy(Warehouse $warehouse): RedirectResponse
    {
        if ($warehouse->is_default) {
            return redirect()
                ->route('admin.warehouses.index')
                ->with('error', 'Warehouse default tidak bisa dihapus.');
        }

        if ($warehouse->stocks()->exists()) {
            return redirect()
                ->route('admin.warehouses.index')
                ->with('error', 'Warehouse tidak bisa dihapus karena sudah memiliki data stok.');
        }

        $warehouse->delete();

        return redirect()
            ->route('admin.warehouses.index')
            ->with('success', 'Warehouse berhasil dihapus.');
    }
}