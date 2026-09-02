<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VaccinationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
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
            'name'              => ['required', 'string', 'max:255'],
            'application_date'  => ['required', 'date'],
            'next_dose_date'    => ['nullable', 'date'],
            'batch'             => ['nullable', 'string', 'max:255'],
            'manufacturer'      => ['nullable', 'string', 'max:255'],
            'observations'      => ['nullable', 'string']
        ];
    }
}
