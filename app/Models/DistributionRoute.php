<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

class DistributionRoute extends Model
{
    protected $fillable = ['vehicle_id', 'route_date', 'status'];

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function shippingGuides(): HasMany
    {
        return $this->hasMany(ShippingGuide::class, 'route_id');
    }
}
