<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

class ProductReturn extends Model
{
    protected $fillable = ['customer_id', 'return_date', 'reason'];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function returnDetails(): HasMany
    {
        return $this->hasMany(ReturnDetail::class, 'return_id');
    }
}
