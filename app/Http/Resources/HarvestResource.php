<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class HarvestResource extends JsonResource
{
    public function toArray($request): array
    {
        $plantingSeason = $this->whenLoaded('plantingSeason');
        $plot = $plantingSeason?->plot;

        // Calcular días de cultivo
        $cultivationDays = null;
        if ($plantingSeason && $plantingSeason->planting_date && $this->harvest_date) {
            $cultivationDays = $plantingSeason->planting_date->diffInDays($this->harvest_date);
        }

        return [
            'id' => $this->id,
            'planting_season_id' => $this->planting_season_id,
            'harvest_date' => optional($this->harvest_date)->toDateString(),
            'quantity' => $this->quantity,
            'unit' => $this->unit,
            'quality' => $this->quality,
            'observations' => $this->observations,
            'yield_per_hectare' => $this->yield_per_hectare,
            'cultivation_days' => $cultivationDays,

            'planting_season' => $this->whenLoaded('plantingSeason', function () use ($plantingSeason, $plot) {
                return [
                    'id' => $plantingSeason->id,
                    'planting_date' => optional($plantingSeason->planting_date)->toDateString(),
                    'expected_harvest_date' => optional($plantingSeason->expected_harvest_date)->toDateString(),
                    'status' => $plantingSeason->status,

                    'plot' => [
                        'id' => $plot?->id,
                        'code' => $plot?->code,
                        'area_hectares' => $plot?->area_hectares,
                    ],

                    'variety_id' => $plantingSeason->variety_id,
                ];
            }),
        ];
    }
}
