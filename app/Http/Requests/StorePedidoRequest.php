<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePedidoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'carrito' => ['required', 'json'],
            'Pais' => ['required', 'string', 'max:255'],
            'Region' => ['required', 'string', 'max:255'],
            'Ciudad' => ['required', 'string', 'max:255'],
            'Direccion' => ['required', 'string', 'max:500'],
            'Referencia' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'carrito.required' => 'El carrito es obligatorio.',
            'carrito.json' => 'El carrito debe ser un JSON válido.',
            'Pais.required' => 'El país es obligatorio.',
            'Region.required' => 'La región es obligatoria.',
            'Ciudad.required' => 'La ciudad es obligatoria.',
            'Direccion.required' => 'La dirección es obligatoria.',
        ];
    }
}
