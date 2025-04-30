<?php

namespace App\Http\Requests\Product;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProductBuyRequest extends FormRequest
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
            'id' => 'nullable|exists:products,id',
            'product_id' => 'required|numeric|exists:products,id',
            'quantity' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
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
            'product_id.number' => 'El Producto debe ser una cadena de texto',
            'total.required' => 'El Total es obligatorio',
            'total.numeric' => 'El Total debe ser un número',
            'total.min' => 'El Total no puede ser negativo',
            'quantity.required' => 'La cantidad es obligatorio',
            'quantity.numeric' => 'La cantidad debe ser un número',
            'quantity.min' => 'La cantidad no puede ser negativo',
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
