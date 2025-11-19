<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePlotRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'         => ['required', 'string', 'max:255'],
            'area'         => ['required', 'numeric', 'min:0.01'],
            'location'     => ['required', 'string'],
            'soil_type'    => ['nullable', 'string'],
            'soil_ph'      => ['nullable', 'numeric', 'between:0,14'],
            'soil_texture' => ['nullable', 'string'],
        ];
    }
}
