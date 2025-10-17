<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaintenanceRecord extends Model
{
    protected $fillable = ['machinery_id', 'maintenance_date', 'type', 'description', 'cost'];

    public function machinery(): BelongsTo
    {
        return $this->belongsTo(Machinery::class);
    }
}
