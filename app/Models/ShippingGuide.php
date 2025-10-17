<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShippingGuide extends Model
{
    protected $fillable = ['order_id', 'route_id', 'delivery_point_id', 'guide_number', 'shipping_date'];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function distributionRoute(): BelongsTo
    {
        return $this->belongsTo(DistributionRoute::class, 'route_id');
    }

    public function deliveryPoint(): BelongsTo
    {
        return $this->belongsTo(DeliveryPoint::class);
    }
}
