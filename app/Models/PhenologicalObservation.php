<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PhenologicalObservation extends Model
{
    protected $fillable = ['planting_season_id', 'stage_id', 'observation_date', 'notes'];

    public function plantingSeason(): BelongsTo
    {
        return $this->belongsTo(PlantingSeason::class);
    }

    public function phenologicalStage(): BelongsTo
    {
        return $this->belongsTo(PhenologicalStage::class, 'stage_id');
    }
}
