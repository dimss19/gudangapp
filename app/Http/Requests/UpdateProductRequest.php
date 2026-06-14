<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Hanya admin yang boleh update produk
        return auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'name'            => 'required|string|max:255',
            'sku'             => 'required|string|max:100|unique:products,sku,' . $this->product->id,
            'category_id'     => 'required|exists:categories,id',
            'supplier_id'     => 'required|exists:suppliers,id',
            'description'     => 'nullable|string',
            'purchase_price'  => 'required|numeric|min:0',
            'selling_price'   => 'required|numeric|min:0',
            'unit'            => 'required|string|max:50',
            'minimum_stock'   => 'required|integer|min:0',
            'is_active'       => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'        => 'Nama produk wajib diisi',
            'sku.required'         => 'SKU wajib diisi',
            'sku.unique'           => 'SKU sudah digunakan produk lain',
            'category_id.required' => 'Kategori wajib dipilih',
            'supplier_id.required' => 'Supplier wajib dipilih',
        ];
    }
}
