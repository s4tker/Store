<?php

// archivo que revisa datos antes de guardarlos
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

// esta clase valida datos para crear pedidos
class StorePedidoRequest extends FormRequest
{
    // permite usar la solicitud
    public function authorize(): bool
    {
        return true;
    }
    // indica lo que se debe llenar

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
    // muestra mensajes claros

    public function messages(): array
    {
        return [
            'carrito.required' => 'El carrito es obligatorio.',
            'carrito.json' => 'El carrito debe ser un JSON válido.',
            'Documento.required' => 'El DNI es obligatorio.',
            'Documento.regex' => 'El DNI debe tener exactamente 8 dígitos.',
            'Nombre.required' => 'El nombre es obligatorio.',
            'Apellidos.required' => 'Los apellidos son obligatorios.',
            'Correo.required' => 'El correo es obligatorio.',
            'Correo.email' => 'Ingresa un correo válido.',
            'Telefono.required' => 'El teléfono es obligatorio.',
            'Telefono.regex' => 'El teléfono peruano debe tener 9 dígitos y empezar con 9.',
            'DireccionId.exists' => 'La dirección seleccionada no existe.',
            'Region.required_without' => 'La región es obligatoria.',
            'Ciudad.required_without' => 'La ciudad es obligatoria.',
            'Direccion.required_without' => 'La dirección es obligatoria.',
        ];
    }
    // limpia datos antes de validar

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
