<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVeiculoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'placa' => 'required|string|max:10|unique:veiculos,placa',
            'descricao' => 'nullable|string|max:255',
            'ativo' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'placa.required' => 'A placa é obrigatória.',
            'placa.max' => 'A placa não pode ter mais de 10 caracteres.',
            'placa.unique' => 'Esta placa já está cadastrada.',
        ];
    }
}
