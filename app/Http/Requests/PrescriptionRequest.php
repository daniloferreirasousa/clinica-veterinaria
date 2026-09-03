<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class PrescriptionRequest extends FormRequest
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
                'animal_id'       => ['required', 'exists:animals,id'],
                'consultation_id' => ['nullable', 'exists:consutations,id'],
                'date'            => ['required', 'date'],
                'observations'    => ['nullable', 'string'],
        ];
    }
}
