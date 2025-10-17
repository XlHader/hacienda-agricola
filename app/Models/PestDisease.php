<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PestDisease extends Model
{
    protected $fillable = ['name', 'type', 'description'];

    public function phytosanitaryIncidents(): HasMany
    {
        return $this->hasMany(PhytosanitaryIncident::class);
    }
}
