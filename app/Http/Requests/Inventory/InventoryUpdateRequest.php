<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;

class InventoryUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'quantity' => 'required|integer|min:0',
            'location' => 'required|string|max:255',
            'status' => 'required|string|in:active,inactive,discontinued'
        ];
    }

    public function messages()
    {
        return [
            'quantity.required' => 'La cantidad es obligatoria',
            'quantity.integer' => 'La cantidad debe ser un número entero',
            'quantity.min' => 'La cantidad no puede ser negativa',
            'location.required' => 'La ubicación es obligatoria',
            'location.max' => 'La ubicación no puede exceder los 255 caracteres',
            'status.required' => 'El estado es obligatorio',
            'status.in' => 'El estado debe ser activo, inactivo o descontinuado'
        ];
    }
} 