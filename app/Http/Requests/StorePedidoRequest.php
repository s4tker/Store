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
            'Documento' => ['required', 'regex:/^\d{8}$/'],
            'Nombre' => ['required', 'string', 'max:60'],
            'Apellidos' => ['required', 'string', 'max:100'],
            'Correo' => ['required', 'email', 'max:120'],
            'Telefono' => ['required', 'regex:/^9\d{8}$/'],
            'Pais' => ['nullable', 'string', 'max:40'],
            'DireccionId' => ['nullable', 'integer', 'exists:Direcciones,Id'],
            'Region' => ['nullable', 'required_without:DireccionId', 'string', 'max:50'],
            'Ciudad' => ['nullable', 'required_without:DireccionId', 'string', 'max:50'],
            'Direccion' => ['nullable', 'required_without:DireccionId', 'string', 'max:120'],
            'Referencia' => ['nullable', 'string', 'max:120'],
            'MetodoPago' => ['nullable', 'string', 'max:50'],
        ];
    }

    public function messages(): array
    {
        return [
            'carrito.required' => 'El carrito es obligatorio.',
            'carrito.json' => 'El carrito debe ser un JSON valido.',
            'Documento.required' => 'El DNI es obligatorio.',
            'Documento.regex' => 'El DNI debe tener exactamente 8 digitos.',
            'Nombre.required' => 'El nombre es obligatorio.',
            'Apellidos.required' => 'Los apellidos son obligatorios.',
            'Correo.required' => 'El correo es obligatorio.',
            'Correo.email' => 'Ingresa un correo valido.',
            'Telefono.required' => 'El telefono es obligatorio.',
            'Telefono.regex' => 'El telefono peruano debe tener 9 digitos y empezar con 9.',
            'DireccionId.exists' => 'La direccion seleccionada no existe.',
            'Region.required_without' => 'La region es obligatoria.',
            'Ciudad.required_without' => 'La ciudad es obligatoria.',
            'Direccion.required_without' => 'La direccion es obligatoria.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'Documento' => preg_replace('/\D+/', '', (string) $this->input('Documento')),
            'Telefono' => preg_replace('/\D+/', '', (string) $this->input('Telefono')),
            'DireccionId' => $this->input('DireccionId') ?: null,
            'Pais' => 'Peru',
            'Nombre' => trim((string) $this->input('Nombre')),
            'Apellidos' => trim((string) $this->input('Apellidos')),
            'Correo' => mb_strtolower(trim((string) $this->input('Correo'))),
            'Region' => trim((string) $this->input('Region')),
            'Ciudad' => trim((string) $this->input('Ciudad')),
            'Direccion' => trim((string) $this->input('Direccion')),
            'Referencia' => trim((string) $this->input('Referencia', $this->input('Notas', ''))),
        ]);
    }
}
