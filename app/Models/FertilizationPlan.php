<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

class FertilizationPlan extends Model
{
    protected $fillable = ['planting_season_id', 'name', 'description'];

    public function plantingSeason(): BelongsTo
    {
        return $this->belongsTo(PlantingSeason::class);
    }

    public function fertilizerApplications(): HasMany
    {
        return $this->hasMany(FertilizerApplication::class);
    }
}
