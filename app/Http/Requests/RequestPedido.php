<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequestPedido extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'productos' => 'nullable|array',
            'nota' => 'nullable|string|max:255',
        ];
    }
    public function messages(): array
    {
        return [
            'productos.array' => 'El formato de los productos no es válido.',
            'nota.string' => 'La nota debe ser una cadena de texto.',
            'nota.max' => 'La nota no debe exceder los 255 caracteres.',
        ];
    }
}
