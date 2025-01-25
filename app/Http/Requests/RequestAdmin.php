<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RequestAdmin extends FormRequest
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
            // Validación de los datos del formulario

            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'dni' => 'nullable|string|max:8|unique:persona,dni',
            'telefono' => 'required|string|max:15|unique:users,usuario',
            'password' => 'required|string|min:6',
            'direccion' => 'nullable|string|max:255',
            'tipo' => 'required',
            'nota' => 'nullable|string|max:500',
            'correo' => 'required|unique:persona,correo',


        ];
    }
    public function messages(): array
    {
        return [
            'tipo.required' => 'El campo tipo es obligatorio.',
            'nombres.required' => 'El campo nombres es obligatorio.',
            'nombres.string' => 'El campo nombres debe ser una cadena de texto.',
            'nombres.max' => 'El campo nombres no puede tener mas de 255 caracteres.',

            'apellidos.required' => 'El campo apellidos es obligatorio.',
            'apellidos.string' => 'El campo apellidos debe ser una cadena de texto.',
            'apellidos.max' => 'El campo apellidos no puede tener mas de 255 caracteres.',

            'dni.string' => 'El campo DNI debe ser una cadena de texto.',
            'dni.max' => 'El campo DNI no puede tener mas de 8 caracteres.',
            'dni.unique' => 'El DNI ingresado ya existe en la base de datos.',

            'telefono.required' => 'El campo telefono es obligatorio.',
            'telefono.string' => 'El campo telefono debe ser una cadena de texto.',
            'telefono.max' => 'El campo telefono no puede tener mas de 15 caracteres.',
            'telefono.unique' => 'El telefono ingresado ya existe en la base de datos.',

            'password.required' => 'El campo contraseña es obligatorio.',
            'password.string' => 'El campo contraseña debe ser una cadena de texto.',
            'password.min' => 'El campo contraseña debe tener al menos 6 caracteres.',

            'direccion.string' => 'El campo direccion debe ser una cadena de texto.',
            'direccion.max' => 'El campo direccion no puede tener mas de 255 caracteres.',

            'nota.string' => 'El campo nota debe ser una cadena de texto.',
            'nota.max' => 'El campo nota no puede tener mas de 500 caracteres.',

            'correo.required' => 'El campo correo es obligatorio.',
            'correo.unique' => 'El correo ingresado ya existe en la base de datos.',
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        $response = redirect()
            ->back()
            ->withInput($this->except(['logo', 'imagenes']))
            ->withErrors($validator->errors())
            ->with('empresa', $this->get('empresa_id')); // Agregar la variable a la sesión

        throw new HttpResponseException($response);
    }
}
