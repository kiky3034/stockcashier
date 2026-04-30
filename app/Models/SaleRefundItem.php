<?php

namespace App\Models;

use Database\Factories\SaleRefundItemFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'sale_refund_id',
    'sale_item_id',
    'product_id',
    'product_name',
    'sku',
    'unit_name',
    'quantity',
    'unit_price',
    'subtotal',
])]
class SaleRefundItem extends Model
{
    /** @use HasFactory<SaleRefundItemFactory> */
    use HasFactory;

    public function refund(): BelongsTo
    {
        return $this->belongsTo(SaleRefund::class, 'sale_refund_id');
    }

    public function saleItem(): BelongsTo
    {
        return $this->belongsTo(SaleItem::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}