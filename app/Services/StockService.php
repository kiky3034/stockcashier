<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Product;
use App\Models\Stock;
use App\Models\StockMovement;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StockService
{
    public function increase(
        Product $product,
        Warehouse $warehouse,
        float $quantity,
        string $type,
        ?User $user = null,
        ?string $notes = null,
        ?string $referenceType = null,
        ?int $referenceId = null,
    ): StockMovement {
        return $this->move(
            product: $product,
            warehouse: $warehouse,
            quantityChange: abs($quantity),
            type: $type,
            user: $user,
            notes: $notes,
            referenceType: $referenceType,
            referenceId: $referenceId,
        );
    }

    public function decrease(
        Product $product,
        Warehouse $warehouse,
        float $quantity,
        string $type,
        ?User $user = null,
        ?string $notes = null,
        ?string $referenceType = null,
        ?int $referenceId = null,
    ): StockMovement {
        return $this->move(
            product: $product,
            warehouse: $warehouse,
            quantityChange: -abs($quantity),
            type: $type,
            user: $user,
            notes: $notes,
            referenceType: $referenceType,
            referenceId: $referenceId,
        );
    }

    public function move(
        Product $product,
        Warehouse $warehouse,
        float $quantityChange,
        string $type,
        ?User $user = null,
        ?string $notes = null,
        ?string $referenceType = null,
        ?int $referenceId = null,
    ): StockMovement {
        if (! $product->track_stock) {
            throw ValidationException::withMessages([
                'product_id' => 'Produk ini tidak menggunakan tracking stok.',
            ]);
        }

        if ($quantityChange == 0.0) {
            throw ValidationException::withMessages([
                'quantity' => 'Quantity perubahan stok tidak boleh 0.',
            ]);
        }

        return DB::transaction(function () use (
            $product,
            $warehouse,
            $quantityChange,
            $type,
            $user,
            $notes,
            $referenceType,
            $referenceId
        ) {
            $stock = Stock::query()
                ->where('product_id', $product->id)
                ->where('warehouse_id', $warehouse->id)
                ->lockForUpdate()
                ->first();

            if (! $stock) {
                Stock::create([
                    'product_id' => $product->id,
                    'warehouse_id' => $warehouse->id,
                    'quantity' => 0,
                ]);

                $stock = Stock::query()
                    ->where('product_id', $product->id)
                    ->where('warehouse_id', $warehouse->id)
                    ->lockForUpdate()
                    ->first();
            }

            $quantityBefore = (float) $stock->quantity;
            $quantityAfter = $quantityBefore + $quantityChange;

            if ($quantityAfter < 0) {
                throw ValidationException::withMessages([
                    'quantity' => 'Stok tidak mencukupi. Stok tersedia: ' . $quantityBefore,
                ]);
            }

            $stock->update([
                'quantity' => $quantityAfter,
            ]);

            return StockMovement::create([
                'product_id' => $product->id,
                'warehouse_id' => $warehouse->id,
                'user_id' => $user?->id,
                'type' => $type,
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
                'quantity_before' => $quantityBefore,
                'quantity_change' => $quantityChange,
                'quantity_after' => $quantityAfter,
                'notes' => $notes,
            ]);
        });
    }
}