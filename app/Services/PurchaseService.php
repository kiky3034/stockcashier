<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Supplier;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Services\ActivityLogService;

class PurchaseService
{
    public function __construct(
        private readonly StockService $stockService,
        private readonly ActivityLogService $activityLog,
    ) {
    }

    public function createPurchase(array $data, User $user): Purchase
    {
        return DB::transaction(function () use ($data, $user) {
            $supplier = Supplier::findOrFail($data['supplier_id']);
            $warehouse = Warehouse::findOrFail($data['warehouse_id']);

            $items = collect($data['items'])
                ->filter(fn ($item) => isset($item['product_id'], $item['quantity'], $item['unit_cost']))
                ->map(function ($item) {
                    return [
                        'product_id' => (int) $item['product_id'],
                        'quantity' => (float) $item['quantity'],
                        'unit_cost' => (float) $item['unit_cost'],
                    ];
                })
                ->filter(fn ($item) => $item['quantity'] > 0)
                ->values();

            if ($items->isEmpty()) {
                throw ValidationException::withMessages([
                    'items' => 'Minimal pilih 1 produk untuk pembelian.',
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
                $unitCost = $item['unit_cost'];
                $lineSubtotal = $quantity * $unitCost;

                $subtotal += $lineSubtotal;

                $preparedItems[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'unit_cost' => $unitCost,
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

            $purchase = Purchase::create([
                'purchase_number' => $this->generatePurchaseNumber(),
                'supplier_id' => $supplier->id,
                'warehouse_id' => $warehouse->id,
                'user_id' => $user->id,
                'subtotal' => $subtotal,
                'discount_amount' => $discountAmount,
                'tax_amount' => $taxAmount,
                'total_amount' => $totalAmount,
                'status' => 'completed',
                'notes' => $data['notes'] ?? null,
                'purchased_at' => now(),
            ]);

            foreach ($preparedItems as $preparedItem) {
                /** @var Product $product */
                $product = $preparedItem['product'];

                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'sku' => $product->sku,
                    'unit_name' => $product->unit?->abbreviation,
                    'quantity' => $preparedItem['quantity'],
                    'unit_cost' => $preparedItem['unit_cost'],
                    'subtotal' => $preparedItem['subtotal'],
                ]);

                if ($product->track_stock) {
                    $this->stockService->increase(
                        product: $product,
                        warehouse: $warehouse,
                        quantity: $preparedItem['quantity'],
                        type: 'purchase',
                        user: $user,
                        notes: 'Purchase ' . $purchase->purchase_number,
                        referenceType: Purchase::class,
                        referenceId: $purchase->id,
                    );
                }

                if (! empty($data['update_cost_price'])) {
                    $product->update([
                        'cost_price' => $preparedItem['unit_cost'],
                    ]);
                }
            }

            // ✅ Tambahkan activity log
            $this->activityLog->log(
                event: 'purchase_created',
                description: 'Purchase dibuat: ' . $purchase->purchase_number,
                subject: $purchase,
                properties: [
                    'purchase_number' => $purchase->purchase_number,
                    'supplier_id' => $supplier->id,
                    'supplier_name' => $supplier->name,
                    'warehouse_id' => $warehouse->id,
                    'warehouse_name' => $warehouse->name,
                    'subtotal' => $purchase->subtotal,
                    'discount_amount' => $purchase->discount_amount,
                    'tax_amount' => $purchase->tax_amount,
                    'total_amount' => $purchase->total_amount,
                    'items' => collect($preparedItems)->map(function ($item) {
                        return [
                            'product_id' => $item['product']->id,
                            'product_name' => $item['product']->name,
                            'sku' => $item['product']->sku,
                            'quantity' => $item['quantity'],
                            'unit_cost' => $item['unit_cost'],
                            'subtotal' => $item['subtotal'],
                        ];
                    })->values()->all(),
                ],
                user: $user,
            );

            return $purchase->load(['items.product', 'supplier', 'warehouse', 'user']);
        });
    }

    private function generatePurchaseNumber(): string
    {
        $date = now()->format('Ymd');

        $countToday = Purchase::query()
            ->whereDate('purchased_at', now()->toDateString())
            ->count() + 1;

        return 'PO-' . $date . '-' . str_pad((string) $countToday, 5, '0', STR_PAD_LEFT);
    }
}