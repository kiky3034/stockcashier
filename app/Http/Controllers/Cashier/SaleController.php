<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use App\Models\Sale;
use App\Services\ActivityLogService;
use App\Services\SaleService;
use Illuminate\Http\RedirectResponse;
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

        $sale->load(['items.product', 'items.refundItems', 'payments', 'refunds.user', 'cashier', 'warehouse']);

        return view('pages.cashier.sales.show', [
            'sale' => $sale,
        ]);
    }

    public function receipt(Sale $sale, Request $request, ActivityLogService $activityLog): View
    {
        if ($request->user()->hasRole('cashier') && ! $request->user()->hasRole('admin')) {
            abort_if($sale->cashier_id !== $request->user()->id, 403);
        }

        $sale->load(['items.product', 'payments', 'cashier', 'warehouse']);

        $settings = AppSetting::values([
            'store_name' => 'StockCashier Store',
            'store_address' => '',
            'store_phone' => '',
            'store_email' => '',
            'store_logo' => null,
            'receipt_footer' => 'Terima kasih sudah berbelanja.',
            'currency_prefix' => 'Rp',
            'receipt_paper_size' => '80mm',
            'receipt_auto_print' => 'false',
            'receipt_show_logo' => 'true',
        ]);

        $activityLog->log(
            event: 'receipt_viewed',
            description: 'Receipt dibuka: ' . $sale->invoice_number,
            subject: $sale,
            properties: [
                'invoice_number' => $sale->invoice_number,
                'receipt_paper_size' => $settings['receipt_paper_size'] ?? '80mm',
            ],
            user: $request->user(),
        );

        return view('pages.cashier.sales.receipt', [
            'sale' => $sale,
            'settings' => $settings,
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
