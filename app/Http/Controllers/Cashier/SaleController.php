<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Services\SaleService;
use Illuminate\Http\RedirectResponse;

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

        $sale->load(['items.product', 'items.refundItems', 'payments', 'refunds.user', 'cashier', 'warehouse']);

        return view('pages.cashier.sales.show', [
            'sale' => $sale,
        ]);
    }

    public function receipt(Sale $sale, Request $request): View
    {
        if ($request->user()->hasRole('cashier') && ! $request->user()->hasRole('admin')) {
            abort_if($sale->cashier_id !== $request->user()->id, 403);
        }

        $sale->load(['items.product', 'payments', 'cashier', 'warehouse']);

        return view('pages.cashier.sales.receipt', [
            'sale' => $sale,
        ]);
    }

    public function void(Sale $sale, Request $request, SaleService $saleService): RedirectResponse
    {
        if ($request->user()->hasRole('cashier') && ! $request->user()->hasRole('admin')) {
            abort_if($sale->cashier_id !== $request->user()->id, 403);
        }

        $validated = $request->validate([
            'reason' => ['nullable', 'string', 'max:1000'],
        ]);

        $saleService->voidSale(
            sale: $sale,
            user: $request->user(),
            reason: $validated['reason'] ?? null,
        );

        return redirect()
            ->route('cashier.sales.show', $sale)
            ->with('success', 'Transaksi berhasil di-void dan stok sudah dikembalikan.');
    }
}