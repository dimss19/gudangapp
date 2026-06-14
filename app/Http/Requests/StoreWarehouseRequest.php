<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\WarehouseTypeEnum;

class StoreWarehouseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'name'      => 'required|string|max:255',
            'code'      => 'required|string|max:50|unique:warehouses,code',
            'type'      => ['required', 'in:' . implode(',', array_column(WarehouseTypeEnum::cases(), 'value'))],
            'address'   => 'nullable|string',
            'phone'     => 'nullable|string|max:20',
            'is_active' => 'boolean',
        ];
    }
}
