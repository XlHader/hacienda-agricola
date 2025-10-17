<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plot extends Model
{
    protected $fillable = ['code', 'area_hectares', 'soil_type', 'soil_analysis'];

    public function plantingSeasons(): HasMany
    {
        return $this->hasMany(PlantingSeason::class);
    }

    public function irrigationSystems(): HasMany
    {
        return $this->hasMany(IrrigationSystem::class);
    }
}
