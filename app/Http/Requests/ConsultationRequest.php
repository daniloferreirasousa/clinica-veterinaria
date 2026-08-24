<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ConsultationRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'animal_id'         => ['required', 'exists:animals,id'],
            'veterinarian_id'   => ['required', 'exists:users,id'],
            'date_time'         => ['required', 'date'],
            'status'            => ['required', 'in:agendada,em_andamento,concluida,cancelada'],
            'reason'            => ['required', 'string', 'max:1000'],
            'diagnosis'         => ['nullable', 'string', 'max:2000'],
            'prescription'      => ['nullable', 'string', 'max:2000'],
            'value'             => ['required', 'numeric', 'min:0', 'max:99999.99'],
        ];
    }


    public function messages(): array
    {
        return [
            'animal_id.required'        => 'Selecione o paciente (animal).',
            'animal_id.exists'          => 'O paciente selecionado é inválido.',
            'veterinarian_id.required'  => 'Selecione o veterinário responsável.',
            'date_time.required'        => 'Informe a data e a hora da consulta.',
            'status.required'           => 'Selecione o status do atendimento.',
            'reason.required'           => 'Descreva o motivo principal da consulta.',
            'value.required'            => 'Informe o valor da consulta.',
            'value.numeric'             => 'O valor deve ser um número válido.',
        ];
    }
}
