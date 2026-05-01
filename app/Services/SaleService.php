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
use App\Models\SaleRefund;
use App\Models\SaleRefundItem;
use App\Services\ActivityLogService;

class SaleService
{
    public function __construct(
        private readonly StockService $stockService,
        private readonly ActivityLogService $activityLog,
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

            $this->activityLog->log(
                event: 'sale_created',
                description: 'Sale dibuat: ' . $sale->invoice_number,
                subject: $sale,
                properties: [
                    'invoice_number' => $sale->invoice_number,
                    'cashier_id' => $cashier->id,
                    'cashier_name' => $cashier->name,
                    'warehouse_id' => $warehouse->id,
                    'warehouse_name' => $warehouse->name,
                    'subtotal' => $sale->subtotal,
                    'discount_amount' => $sale->discount_amount,
                    'tax_amount' => $sale->tax_amount,
                    'total_amount' => $sale->total_amount,
                    'paid_amount' => $sale->paid_amount,
                    'change_amount' => $sale->change_amount,
                    'payment_method' => $data['payment_method'],
                    'items' => collect($preparedItems)->map(function ($item) {
                        return [
                            'product_id' => $item['product']->id,
                            'product_name' => $item['product']->name,
                            'sku' => $item['product']->sku,
                            'quantity' => $item['quantity'],
                            'unit_price' => $item['unit_price'],
                            'cost_price' => $item['cost_price'],
                            'subtotal' => $item['subtotal'],
                        ];
                    })->values()->all(),
                ],
                user: $cashier,
            );

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

    private function generateRefundNumber(): string
    {
        $date = now()->format('Ymd');

        $countToday = SaleRefund::query()
            ->whereDate('refunded_at', now()->toDateString())
            ->count() + 1;

        return 'RFN-' . $date . '-' . str_pad((string) $countToday, 5, '0', STR_PAD_LEFT);
    }

    private function updateSaleRefundStatus(Sale $sale): void
    {
        $sale->load(['items.refundItems']);

        $allItemsFullyRefunded = true;
        $hasRefund = false;

        foreach ($sale->items as $item) {
            $refundedQuantity = (float) $item->refundItems()->sum('quantity');

            if ($refundedQuantity > 0) {
                $hasRefund = true;
            }

            if ($refundedQuantity < (float) $item->quantity) {
                $allItemsFullyRefunded = false;
            }
        }

        if ($allItemsFullyRefunded) {
            $sale->update([
                'status' => 'refunded',
            ]);

            return;
        }

        if ($hasRefund) {
            $sale->update([
                'status' => 'partially_refunded',
            ]);
        }
    }

    public function voidSale(Sale $sale, User $user, ?string $reason = null): Sale
    {
        return DB::transaction(function () use ($sale, $user, $reason) {
            $sale = Sale::query()
                ->whereKey($sale->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($sale->status !== 'completed') {
                throw ValidationException::withMessages([
                    'sale' => 'Transaksi ini tidak bisa di-void karena statusnya bukan completed.',
                ]);
            }

            $sale->load(['items.product', 'warehouse']);

            foreach ($sale->items as $item) {
                $product = $item->product;

                if ($product && $product->track_stock) {
                    $this->stockService->increase(
                        product: $product,
                        warehouse: $sale->warehouse,
                        quantity: (float) $item->quantity,
                        type: 'sale_void',
                        user: $user,
                        notes: 'Void sale invoice ' . $sale->invoice_number . ($reason ? ' - ' . $reason : ''),
                        referenceType: Sale::class,
                        referenceId: $sale->id,
                    );
                }
            }

            $voidNote = 'VOID by ' . $user->name . ' at ' . now()->format('Y-m-d H:i:s');

            if ($reason) {
                $voidNote .= ' - Reason: ' . $reason;
            }

            $sale->update([
                'status' => 'voided',
                'notes' => trim(($sale->notes ? $sale->notes . "\n" : '') . $voidNote),
            ]);

            $this->activityLog->log(
                event: 'sale_voided',
                description: 'Sale di-void: ' . $sale->invoice_number,
                subject: $sale,
                properties: [
                    'invoice_number' => $sale->invoice_number,
                    'reason' => $reason,
                    'total_amount' => $sale->total_amount,
                    'items' => $sale->items->map(function ($item) {
                        return [
                            'product_id' => $item->product_id,
                            'product_name' => $item->product_name,
                            'sku' => $item->sku,
                            'quantity_restored' => $item->quantity,
                        ];
                    })->values()->all(),
                ],
                user: $user,
            );

            return $sale->load(['items', 'payments', 'cashier', 'warehouse']);
        });
    }

    public function refundSale(Sale $sale, array $data, User $user): SaleRefund
    {
        return DB::transaction(function () use ($sale, $data, $user) {
            $sale = Sale::query()
                ->whereKey($sale->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($sale->status === 'voided') {
                throw ValidationException::withMessages([
                    'sale' => 'Transaksi voided tidak bisa direfund.',
                ]);
            }

            if ($sale->status === 'refunded') {
                throw ValidationException::withMessages([
                    'sale' => 'Transaksi ini sudah direfund penuh.',
                ]);
            }

            $sale->load(['items.product', 'warehouse']);

            $items = collect($data['items'] ?? [])
                ->filter(fn ($item) => isset($item['sale_item_id'], $item['quantity']))
                ->map(function ($item) {
                    return [
                        'sale_item_id' => (int) $item['sale_item_id'],
                        'quantity' => (float) $item['quantity'],
                    ];
                })
                ->filter(fn ($item) => $item['quantity'] > 0)
                ->values();

            if ($items->isEmpty()) {
                throw ValidationException::withMessages([
                    'items' => 'Minimal pilih 1 item untuk refund.',
                ]);
            }

            $saleItemIds = $items->pluck('sale_item_id')->unique();

            $saleItems = SaleItem::query()
                ->with(['product', 'refundItems'])
                ->where('sale_id', $sale->id)
                ->whereIn('id', $saleItemIds)
                ->get()
                ->keyBy('id');

            if ($saleItems->count() !== $saleItemIds->count()) {
                throw ValidationException::withMessages([
                    'items' => 'Ada item refund yang tidak valid.',
                ]);
            }

            $preparedItems = [];
            $totalRefund = 0;

            foreach ($items as $item) {
                /** @var SaleItem $saleItem */
                $saleItem = $saleItems[$item['sale_item_id']];

                $alreadyRefunded = (float) $saleItem->refundItems()->sum('quantity');
                $refundableQuantity = max((float) $saleItem->quantity - $alreadyRefunded, 0);
                $refundQuantity = (float) $item['quantity'];

                if ($refundQuantity > $refundableQuantity) {
                    throw ValidationException::withMessages([
                        'items' => 'Quantity refund untuk produk ' . $saleItem->product_name . ' melebihi sisa quantity yang bisa direfund.',
                    ]);
                }

                $subtotal = $refundQuantity * (float) $saleItem->unit_price;
                $totalRefund += $subtotal;

                $preparedItems[] = [
                    'sale_item' => $saleItem,
                    'product' => $saleItem->product,
                    'quantity' => $refundQuantity,
                    'unit_price' => (float) $saleItem->unit_price,
                    'subtotal' => $subtotal,
                ];
            }

            $refund = SaleRefund::create([
                'refund_number' => $this->generateRefundNumber(),
                'sale_id' => $sale->id,
                'user_id' => $user->id,
                'total_amount' => $totalRefund,
                'method' => $data['method'],
                'status' => 'completed',
                'reason' => $data['reason'] ?? null,
                'refunded_at' => now(),
            ]);

            foreach ($preparedItems as $preparedItem) {
                /** @var SaleItem $saleItem */
                $saleItem = $preparedItem['sale_item'];
                $product = $preparedItem['product'];

                SaleRefundItem::create([
                    'sale_refund_id' => $refund->id,
                    'sale_item_id' => $saleItem->id,
                    'product_id' => $product->id,
                    'product_name' => $saleItem->product_name,
                    'sku' => $saleItem->sku,
                    'unit_name' => $saleItem->unit_name,
                    'quantity' => $preparedItem['quantity'],
                    'unit_price' => $preparedItem['unit_price'],
                    'subtotal' => $preparedItem['subtotal'],
                ]);

                if ($product && $product->track_stock) {
                    $this->stockService->increase(
                        product: $product,
                        warehouse: $sale->warehouse,
                        quantity: $preparedItem['quantity'],
                        type: 'sale_refund',
                        user: $user,
                        notes: 'Refund invoice ' . $sale->invoice_number . ' / ' . $refund->refund_number,
                        referenceType: SaleRefund::class,
                        referenceId: $refund->id,
                    );
                }
            }

            $this->updateSaleRefundStatus($sale);
            $this->activityLog->log(
                event: 'sale_refunded',
                description: 'Refund dibuat: ' . $refund->refund_number . ' untuk invoice ' . $sale->invoice_number,
                subject: $refund,
                properties: [
                    'refund_number' => $refund->refund_number,
                    'invoice_number' => $sale->invoice_number,
                    'sale_id' => $sale->id,
                    'method' => $refund->method,
                    'total_amount' => $refund->total_amount,
                    'reason' => $refund->reason,
                    'items' => collect($preparedItems)->map(function ($item) {
                        return [
                            'sale_item_id' => $item['sale_item']->id,
                            'product_id' => $item['product']->id,
                            'product_name' => $item['sale_item']->product_name,
                            'sku' => $item['sale_item']->sku,
                            'quantity_refunded' => $item['quantity'],
                            'unit_price' => $item['unit_price'],
                            'subtotal' => $item['subtotal'],
                        ];
                    })->values()->all(),
                ],
                user: $user,
            );

            return $refund->load(['sale', 'items.product', 'user']);
        });
    }
}