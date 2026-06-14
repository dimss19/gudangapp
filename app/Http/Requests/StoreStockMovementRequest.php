<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Product;

class StoreStockMovementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id'   => ['required', 'exists:products,id'],
            'warehouse_id' => ['required', 'exists:warehouses,id'],
            'type'         => ['required', 'in:' . Product::STOCK_IN . ',' . Product::STOCK_OUT],
            'quantity'     => ['required', 'numeric', 'min:0.01'],
            'price'        => ['nullable', 'numeric', 'min:0'],
            'notes'        => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'type.in'        => 'Tipe stok harus masuk atau keluar',
            'quantity.min'   => 'Jumlah stok minimal 1',
            'product_id.exists'   => 'Produk tidak valid',
            'warehouse_id.exists' => 'Gudang tidak valid',
        ];
    }
}
