<?php

namespace App\Models;

use Database\Factories\SalePaymentFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'sale_id',
    'method',
    'amount',
    'reference_number',
    'paid_at',
])]
class SalePayment extends Model
{
    /** @use HasFactory<SalePaymentFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'paid_at' => 'datetime',
        ];
    }

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }
}