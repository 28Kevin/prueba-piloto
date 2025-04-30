<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;

class InventoryUpdateQuantityRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'quantity' => 'required|integer|min:0'
        ];
    }

    public function messages()
    {
        return [
            'quantity.required' => 'La cantidad es obligatoria',
            'quantity.integer' => 'La cantidad debe ser un número entero',
            'quantity.min' => 'La cantidad no puede ser negativa'
        ];
    }
} 