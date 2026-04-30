<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\SaleRefund;
use App\Services\SaleService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SaleRefundController extends Controller
{
    public function create(Sale $sale, Request $request): View
    {
        if ($request->user()->hasRole('cashier') && ! $request->user()->hasRole('admin')) {
            abort_if($sale->cashier_id !== $request->user()->id, 403);
        }

        abort_if($sale->status === 'voided', 403, 'Transaksi voided tidak bisa direfund.');
        abort_if($sale->status === 'refunded', 403, 'Transaksi ini sudah direfund penuh.');

        $sale->load(['items.refundItems', 'cashier', 'warehouse']);

        return view('pages.cashier.refunds.create', [
            'sale' => $sale,
        ]);
    }

    public function store(Sale $sale, Request $request, SaleService $saleService): RedirectResponse
    {
        if ($request->user()->hasRole('cashier') && ! $request->user()->hasRole('admin')) {
            abort_if($sale->cashier_id !== $request->user()->id, 403);
        }

        $validated = $request->validate([
            'method' => ['required', 'in:cash,transfer,qris,card'],
            'reason' => ['nullable', 'string', 'max:1000'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.sale_item_id' => ['required', 'exists:sale_items,id'],
            'items.*.quantity' => ['nullable', 'numeric', 'min:0'],
        ]);

        $refund = $saleService->refundSale(
            sale: $sale,
            data: $validated,
            user: $request->user(),
        );

        return redirect()
            ->route('cashier.refunds.show', $refund)
            ->with('success', 'Refund berhasil disimpan dan stok sudah dikembalikan.');
    }

    public function show(SaleRefund $refund, Request $request): View
    {
        $refund->load(['sale.cashier', 'sale.warehouse', 'items.product', 'user']);

        if ($request->user()->hasRole('cashier') && ! $request->user()->hasRole('admin')) {
            abort_if($refund->sale->cashier_id !== $request->user()->id, 403);
        }

        return view('pages.cashier.refunds.show', [
            'refund' => $refund,
        ]);
    }
}