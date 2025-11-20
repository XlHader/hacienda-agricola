<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHarvestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'planting_season_id' => 'sometimes|required|exists:planting_seasons,id',
            'harvest_date'       => 'sometimes|required|date',
            'quantity'           => 'sometimes|required|numeric|min:0.01',
            'unit'               => 'sometimes|required|string|max:20',
            'quality'            => 'sometimes|required|in:extra,first,second,discard',
            'observations'       => 'sometimes|nullable|string',
        ];
    }
}
