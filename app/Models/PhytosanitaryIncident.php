<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

class PhytosanitaryIncident extends Model
{
    protected $fillable = ['planting_season_id', 'pest_disease_id', 'detection_date', 'severity'];

    public function plantingSeason(): BelongsTo
    {
        return $this->belongsTo(PlantingSeason::class);
    }

    public function pestDisease(): BelongsTo
    {
        return $this->belongsTo(PestDisease::class);
    }

    public function phytosanitaryTreatments(): HasMany
    {
        return $this->hasMany(PhytosanitaryTreatment::class, 'incident_id');
    }
}
