<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

class PhenologicalStage extends Model
{
    protected $fillable = ['crop_id', 'name', 'order'];

    public function crop(): BelongsTo
    {
        return $this->belongsTo(Crop::class);
    }

    public function phenologicalObservations(): HasMany
    {
        return $this->hasMany(PhenologicalObservation::class, 'stage_id');
    }
}
