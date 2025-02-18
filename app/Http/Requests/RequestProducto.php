<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequestProducto extends FormRequest
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
            'descripcion' => 'required|string|max:255', // Validación para cadenas con un límite de caracteres
            'precio' => 'required|numeric|min:0',      // Asegura que el precio sea numérico y no negativo
        ];
    }

    public function messages(): array
    {
        return [
            'descripcion.required' => 'La descripcion del producto es requerida.',
            'descripcion.string' => 'La descripcion debe ser un texto valido.',
            'descripcion.max' => 'La descripcion no puede tener más de 255 caracteres.',
            'precio.required' => 'El precio del producto es obligatorio.',
            'precio.numeric' => 'El precio debe ser un valor numérico.',
            'precio.min' => 'El precio no puede ser negativo.',
        ];
    }
}
