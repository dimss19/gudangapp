<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'           => ['required', 'string', 'max:255'],
            'sku'            => ['required', 'string', 'max:100', 'unique:products,sku'],
            'category_id'    => ['required', 'exists:categories,id'],
            'supplier_id'    => ['required', 'exists:suppliers,id'],
            'description'    => ['nullable', 'string'],
            'purchase_price' => ['required', 'numeric', 'min:0'],
            'selling_price'  => ['required', 'numeric', 'min:0'],
            'unit'           => ['required', 'string', 'max:50'],
            'minimum_stock'  => ['required', 'integer', 'min:0'],
            'is_active'      => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'           => 'Nama produk wajib diisi',
            'sku.required'            => 'SKU wajib diisi',
            'sku.unique'              => 'SKU sudah digunakan',
            'category_id.required'    => 'Kategori wajib dipilih',
            'category_id.exists'      => 'Kategori tidak valid',
            'supplier_id.required'    => 'Supplier wajib dipilih',
            'supplier_id.exists'      => 'Supplier tidak valid',
            'purchase_price.required' => 'Harga beli wajib diisi',
            'selling_price.required'  => 'Harga jual wajib diisi',
        ];
    }
}
