<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IrrigationSystem extends Model
{
    protected $fillable = ['plot_id', 'water_source_id', 'type'];

    public function plot(): BelongsTo
    {
        return $this->belongsTo(Plot::class);
    }

    public function waterSource(): BelongsTo
    {
        return $this->belongsTo(WaterSource::class);
    }
}
