<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

class DeliveryPoint extends Model
{
    protected $fillable = ['customer_id', 'name', 'address', 'coordinates'];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function shippingGuides(): HasMany
    {
        return $this->hasMany(ShippingGuide::class);
    }
}
