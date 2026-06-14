<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransferStockRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id'          => ['required', 'exists:products,id'],
            'from_warehouse_id'   => ['required', 'exists:warehouses,id', 'different:to_warehouse_id'],
            'to_warehouse_id'     => ['required', 'exists:warehouses,id', 'different:from_warehouse_id'],
            'quantity'            => ['required', 'numeric', 'min:0.01'],
            'notes'               => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'from_warehouse_id.different' => 'Gudang asal dan tujuan tidak boleh sama',
            'to_warehouse_id.different'   => 'Gudang tujuan dan asal tidak boleh sama',
            'quantity.min'                => 'Jumlah transfer minimal 1',
        ];
    }
}
