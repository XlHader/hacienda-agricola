<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicle extends Model
{
    protected $fillable = ['plate', 'model', 'capacity_kg', 'status'];

    public function distributionRoutes(): HasMany
    {
        return $this->hasMany(DistributionRoute::class);
    }
}
