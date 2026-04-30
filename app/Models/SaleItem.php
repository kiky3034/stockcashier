<?php

namespace App\Models;

use Database\Factories\SaleItemFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'sale_id',
    'product_id',
    'product_name',
    'sku',
    'unit_name',
    'quantity',
    'cost_price',
    'unit_price',
    'discount_amount',
    'subtotal',
])]
class SaleItem extends Model
{
    /** @use HasFactory<SaleItemFactory> */
    use HasFactory;

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function refundItems(): HasMany
    {
        return $this->hasMany(SaleRefundItem::class);
    }

    public function getRefundedQuantityAttribute(): float
    {
        return (float) $this->refundItems()->sum('quantity');
    }

    public function getRefundableQuantityAttribute(): float
    {
        return max((float) $this->quantity - $this->refunded_quantity, 0);
    }
}