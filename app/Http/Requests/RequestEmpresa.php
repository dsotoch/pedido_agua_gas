<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class RequestEmpresa extends FormRequest
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
            'codigo' => 'required|string|max:15|min:15',
            'nombre' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:empresa,dominio',
            'direccion' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:1000',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg',
            'logo_vertical' => 'nullable|image|mimes:jpeg,png,jpg',
            'imagenes' => 'nullable|array',
            'imagenes.*' => 'image|mimes:jpeg,png,jpg',
            'whatsapp' => 'nullable|url',
            'facebook' => 'nullable|url',
            'telefono' => 'required|string|max:20|unique:empresa,telefono',
            'servicios' => 'nullable|array',
        ];
    }
    public function messages(): array
    {
        return [
            'codigo.required' => 'El codigo es obligatorio.',
            'codigo.string' => 'El codigo debe ser una cadena de texto.',
            'codigo.max' => 'El codigo no debe superar los 15 caracteres.',
            'codigo.min' => 'El codigo debe tener exactamente 15 caracteres.',

            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.string' => 'El nombre debe ser una cadena de texto.',
            'nombre.max' => 'El nombre no debe superar los 255 caracteres.',

            'slug.required' => 'El slug es obligatorio.',
            'slug.string' => 'El slug debe ser una cadena de texto.',
            'slug.max' => 'El slug no debe superar los 255 caracteres.',
            'slug.unique' => 'El slug ya está registrado, elija uno diferente.',

            'direccion.required' => 'La dirección es obligatoria.',
            'direccion.string' => 'La dirección debe ser una cadena de texto.',
            'direccion.max' => 'La dirección no debe superar los 255 caracteres.',

            'descripcion.string' => 'La descripción debe ser una cadena de texto.',
            'descripcion.max' => 'La descripción no debe superar los 1000 caracteres.',

            'logo.image' => 'El logo debe ser una imagen.',
            'logo.mimes' => 'El logo debe estar en formato jpeg, png o jpg.',
            'logo.max' => 'El logo no debe superar los 2 MB.',

            'logo_vertical.image' => 'El logo debe ser una imagen.',
            'logo_vertical.mimes' => 'El logo debe estar en formato jpeg, png o jpg.',
            'logo_vertical.max' => 'El logo no debe superar los 2 MB.',

            'imagenes.array' => 'Las imágenes deben ser un arreglo.',
            'imagenes.*.image' => 'Cada imagen debe ser un archivo de tipo imagen.',
            'imagenes.*.mimes' => 'Cada imagen debe estar en formato jpeg, png o jpg.',
            'imagenes.*.max' => 'Cada imagen no debe superar los 2 MB.',

            'whatsapp.url' => 'El enlace de WhatsApp debe ser una URL válida.',

            'facebook.url' => 'El enlace de Facebook debe ser una URL válida.',

            'telefono.required' => 'El teléfono es obligatorio.',
            'telefono.string' => 'El teléfono debe ser una cadena de texto.',
            'telefono.max' => 'El teléfono no debe superar los 20 caracteres.',

            'servicios.array' => 'Los servicios deben ser un arreglo.',
            'servicios.*.in' => 'Cada servicio debe ser "agua" o "gas".',
        ];
    }


    protected function failedValidation(Validator $validator)
    {
        $response = redirect()
            ->back()
            ->withInput($this->except(['logo', 'imagenes', 'logo_vertical']))
            ->withErrors($validator->errors());

        throw new HttpResponseException($response);
    }
}
