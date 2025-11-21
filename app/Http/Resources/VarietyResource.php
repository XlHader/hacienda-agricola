<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VarietyResource extends JsonResource
{
    /**
     * Transforma la variedad a un array que se enviará como JSON.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'crop_id' => $this->crop_id,
            'name' => $this->name,
            'characteristics' => $this->characteristics,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
            
            // Relación con cultivo
            'crop' => new CropResource($this->whenLoaded('crop')),
        ];
    }
}
