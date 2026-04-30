<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\SalePayment;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SaleService
{
    public function __construct(
        private readonly StockService $stockService,
    ) {
    }

    public function createSale(array $data, User $cashier): Sale
    {
        return DB::transaction(function () use ($data, $cashier) {
            $warehouse = Warehouse::findOrFail($data['warehouse_id']);

            $items = collect($data['items'])
                ->filter(fn ($item) => isset($item['product_id'], $item['quantity']))
                ->map(function ($item) {
                    return [
                        'product_id' => (int) $item['product_id'],
                        'quantity' => (float) $item['quantity'],
                    ];
                })
                ->filter(fn ($item) => $item['quantity'] > 0)
                ->values();

            if ($items->isEmpty()) {
                throw ValidationException::withMessages([
                    'items' => 'Minimal pilih 1 produk.',
                ]);
            }

            $productIds = $items->pluck('product_id')->unique();

            $products = Product::query()
                ->with(['unit'])
                ->whereIn('id', $productIds)
                ->where('is_active', true)
                ->get()
                ->keyBy('id');

            if ($products->count() !== $productIds->count()) {
                throw ValidationException::withMessages([
                    'items' => 'Ada produk yang tidak valid atau tidak aktif.',
                ]);
            }

            $subtotal = 0;
            $preparedItems = [];

            foreach ($items as $item) {
                /** @var Product $product */
                $product = $products[$item['product_id']];
                $quantity = $item['quantity'];
                $unitPrice = (float) $product->selling_price;
                $lineSubtotal = $quantity * $unitPrice;

                $subtotal += $lineSubtotal;

                $preparedItems[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'cost_price' => (float) $product->cost_price,
                    'subtotal' => $lineSubtotal,
                ];
            }

            $discountAmount = (float) ($data['discount_amount'] ?? 0);
            $taxAmount = (float) ($data['tax_amount'] ?? 0);

            if ($discountAmount < 0 || $taxAmount < 0) {
                throw ValidationException::withMessages([
                    'discount_amount' => 'Diskon dan pajak tidak boleh minus.',
                ]);
            }

            $totalAmount = max($subtotal - $discountAmount + $taxAmount, 0);
            $paidAmount = (float) $data['paid_amount'];

            if ($paidAmount < $totalAmount) {
                throw ValidationException::withMessages([
                    'paid_amount' => 'Nominal bayar kurang dari total transaksi.',
                ]);
            }

            $sale = Sale::create([
                'invoice_number' => $this->generateInvoiceNumber(),
                'cashier_id' => $cashier->id,
                'warehouse_id' => $warehouse->id,
                'subtotal' => $subtotal,
                'discount_amount' => $discountAmount,
                'tax_amount' => $taxAmount,
                'total_amount' => $totalAmount,
                'paid_amount' => $paidAmount,
                'change_amount' => $paidAmount - $totalAmount,
                'status' => 'completed',
                'notes' => $data['notes'] ?? null,
                'sold_at' => now(),
            ]);

            foreach ($preparedItems as $preparedItem) {
                /** @var Product $product */
                $product = $preparedItem['product'];

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'sku' => $product->sku,
                    'unit_name' => $product->unit?->abbreviation,
                    'quantity' => $preparedItem['quantity'],
                    'cost_price' => $preparedItem['cost_price'],
                    'unit_price' => $preparedItem['unit_price'],
                    'discount_amount' => 0,
                    'subtotal' => $preparedItem['subtotal'],
                ]);

                if ($product->track_stock) {
                    $this->stockService->decrease(
                        product: $product,
                        warehouse: $warehouse,
                        quantity: $preparedItem['quantity'],
                        type: 'sale',
                        user: $cashier,
                        notes: 'Sale invoice ' . $sale->invoice_number,
                        referenceType: Sale::class,
                        referenceId: $sale->id,
                    );
                }
            }

            SalePayment::create([
                'sale_id' => $sale->id,
                'method' => $data['payment_method'],
                'amount' => $paidAmount,
                'reference_number' => $data['payment_reference'] ?? null,
                'paid_at' => now(),
            ]);

            return $sale->load(['items', 'payments', 'cashier', 'warehouse']);
        });
    }

    private function generateInvoiceNumber(): string
    {
        $date = now()->format('Ymd');

        $countToday = Sale::query()
            ->whereDate('sold_at', now()->toDateString())
            ->count() + 1;

        return 'INV-' . $date . '-' . str_pad((string) $countToday, 5, '0', STR_PAD_LEFT);
    }
}