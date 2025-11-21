<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class PlantingSeasonResource extends JsonResource
{
    /**
     * Transforma la temporada de siembra a un array que se enviará como JSON.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'plot_id' => $this->plot_id,
            'variety_id' => $this->variety_id,
            'planting_date' => $this->planting_date,
            'expected_harvest_date' => $this->expected_harvest_date,
            'status' => $this->status,
            'days_since_planting' => $this->calculateDaysSincePlanting(),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
            
            // Relaciones
            'plot' => new PlotResource($this->whenLoaded('plot')),
            'variety' => new VarietyResource($this->whenLoaded('variety')),
            'harvests' => HarvestResource::collection($this->whenLoaded('harvests')),
        ];
    }

    /**
     * Calcula los días transcurridos desde la siembra.
     */
    private function calculateDaysSincePlanting(): ?int
    {
        if (!$this->planting_date) {
            return null;
        }

        $plantingDate = Carbon::parse($this->planting_date);
        return $plantingDate->diffInDays(now());
    }
}
