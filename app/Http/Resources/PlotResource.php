<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlotResource extends JsonResource
{
    /**
     * Transforma la parcela a un array que se enviará como JSON.
     */
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'code'           => $this->code,
            'area_hectares'  => (float) $this->area_hectares,
            'soil_type'      => $this->soil_type,
            'soil_analysis'  => $this->soil_analysis,
            'created_at'     => $this->created_at?->toISOString(),
            'updated_at'     => $this->updated_at?->toISOString(),
        ];
    }
}

