<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRefundRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'method' => ['required', 'in:cash,transfer,qris,card'],
            'reason' => ['nullable', 'string', 'max:1000'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.sale_item_id' => ['required', 'exists:sale_items,id'],
            'items.*.quantity' => ['nullable', 'numeric', 'min:0'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'method.required' => 'Pilih metode refund.',
            'method.in' => 'Metode refund tidak valid.',
            'items.required' => 'Minimal pilih 1 item untuk refund.',
            'items.min' => 'Minimal pilih 1 item untuk refund.',
            'items.*.sale_item_id.required' => 'Item sale tidak valid.',
            'items.*.sale_item_id.exists' => 'Item sale tidak ditemukan.',
        ];
    }
}
