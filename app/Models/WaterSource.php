<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WaterSource extends Model
{
    protected $fillable = ['name', 'type', 'capacity'];

    public function irrigationSystems(): HasMany
    {
        return $this->hasMany(IrrigationSystem::class);
    }
}
