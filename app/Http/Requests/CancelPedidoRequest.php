<?php

// archivo que revisa datos antes de guardarlos
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

// esta clase valida datos para cancelar pedidos
class CancelPedidoRequest extends FormRequest
{
    // permite usar la solicitud
    public function authorize(): bool
    {
        return true;
    }
    // indica lo que se debe llenar

    public function rules(): array
    {
        return [];
    }
}
