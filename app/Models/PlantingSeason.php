<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

class PlantingSeason extends Model
{
    protected $fillable = [
        'plot_id',
        'variety_id',
        'planting_date',
        'expected_harvest_date',
        'status'
    ];

    protected $casts = [
        'planting_date' => 'date',
        'expected_harvest_date' => 'date',
    ];

    public function plot(): BelongsTo
    {
        return $this->belongsTo(Plot::class);
    }

    public function variety(): BelongsTo
    {
        return $this->belongsTo(Variety::class);
    }

    public function harvests(): HasMany
    {
        return $this->hasMany(Harvest::class);
    }

    public function taskAssignments(): HasMany
    {
        return $this->hasMany(TaskAssignment::class);
    }

    public function fertilizationPlans(): HasMany
    {
        return $this->hasMany(FertilizationPlan::class);
    }
}
