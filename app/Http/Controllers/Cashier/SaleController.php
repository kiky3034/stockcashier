<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SaleController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->query('search');

        $sales = Sale::query()
            ->with(['cashier', 'warehouse'])
            ->when($request->user()->hasRole('cashier') && ! $request->user()->hasRole('admin'), function ($query) use ($request) {
                $query->where('cashier_id', $request->user()->id);
            })
            ->when($search, function ($query, $search) {
                $query->where('invoice_number', 'like', "%{$search}%");
            })
            ->latest('sold_at')
            ->paginate(10)
            ->withQueryString();

        return view('pages.cashier.sales.index', [
            'sales' => $sales,
            'search' => $search,
        ]);
    }

    public function show(Sale $sale, Request $request): View
    {
        if ($request->user()->hasRole('cashier') && ! $request->user()->hasRole('admin')) {
            abort_if($sale->cashier_id !== $request->user()->id, 403);
        }

        $sale->load(['items.product', 'payments', 'cashier', 'warehouse']);

        return view('pages.cashier.sales.show', [
            'sale' => $sale,
        ]);
    }
}