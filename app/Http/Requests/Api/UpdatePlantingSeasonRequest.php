<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePlantingSeasonRequest extends FormRequest
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
            'plot_id' => ['sometimes', 'exists:plots,id'],
            'variety_id' => ['sometimes', 'exists:varieties,id'],
            'planting_date' => ['sometimes', 'date'],
            'expected_harvest_date' => ['sometimes', 'date', 'after:planting_date'],
            'status' => ['sometimes', 'string', Rule::in(['planned', 'active', 'harvested', 'closed'])],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'plot_id.exists' => 'La parcela seleccionada no existe.',
            'variety_id.exists' => 'La variedad seleccionada no existe.',
            'planting_date.date' => 'La fecha de siembra debe ser una fecha válida.',
            'expected_harvest_date.date' => 'La fecha esperada de cosecha debe ser una fecha válida.',
            'expected_harvest_date.after' => 'La fecha esperada de cosecha debe ser posterior a la fecha de siembra.',
            'status.in' => 'El estado debe ser uno de: planned, active, harvested, closed.',
        ];
    }
}
