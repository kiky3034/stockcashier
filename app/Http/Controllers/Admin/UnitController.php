<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UnitController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->query('search');

        $units = Unit::query()
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('abbreviation', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('pages.admin.units.index', [
            'units' => $units,
            'search' => $search,
        ]);
    }

    public function create(): View
    {
        return view('pages.admin.units.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:units,name'],
            'abbreviation' => ['required', 'string', 'max:20', 'unique:units,abbreviation'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        Unit::create([
            'name' => $validated['name'],
            'abbreviation' => $validated['abbreviation'],
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('admin.units.index')
            ->with('success', 'Unit berhasil ditambahkan.');
    }

    public function show(Unit $unit): View
    {
        return view('pages.admin.units.show', [
            'unit' => $unit,
        ]);
    }

    public function edit(Unit $unit): View
    {
        return view('pages.admin.units.edit', [
            'unit' => $unit,
        ]);
    }

    public function update(Request $request, Unit $unit): RedirectResponse
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('units', 'name')->ignore($unit->id),
            ],
            'abbreviation' => [
                'required',
                'string',
                'max:20',
                Rule::unique('units', 'abbreviation')->ignore($unit->id),
            ],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $unit->update([
            'name' => $validated['name'],
            'abbreviation' => $validated['abbreviation'],
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('admin.units.index')
            ->with('success', 'Unit berhasil diperbarui.');
    }

    public function destroy(Unit $unit): RedirectResponse
    {
        if ($unit->products()->exists()) {
            return redirect()
                ->route('admin.units.index')
                ->with('error', 'Unit tidak bisa dihapus karena masih digunakan oleh produk.');
        }

        $unit->delete();

        return redirect()
            ->route('admin.units.index')
            ->with('success', 'Unit berhasil dihapus.');
    }
}