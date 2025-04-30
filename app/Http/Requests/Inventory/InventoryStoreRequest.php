<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class InventoryStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'id' => 'nullable|exists:inventories,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|numeric|min:0',
        ];
    }

    /**
     * Get custom validation messages.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'id.exists' => 'El ID proporcionado no existe en la base de datos',
            'product_id.required' => 'El Producto es obligatorio',
            'product_id.exists' => 'El ID proporcionado del producto no existe en la base de datos',
            'quantity.required' => 'El precio es obligatorio',
            'quantity.numeric' => 'El precio debe ser un número',
            'quantity.min' => 'El precio no puede ser negativo',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'code' => 422,
            'message' => 'Errores de validación',
            'errors' => $validator->errors(),
        ], 422));
    }
}
