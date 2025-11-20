<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreHarvestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'planting_season_id' => 'required|exists:planting_seasons,id',
            'harvest_date'       => 'required|date',
            'quantity'           => 'required|numeric|min:0.01',
            'unit'               => 'required|string|max:20',
            'quality'            => 'required|in:extra,first,second,discard',
            'observations'       => 'nullable|string',
        ];
    }
}
