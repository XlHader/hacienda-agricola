<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AgriculturalInput extends Model
{
    protected $fillable = ['name', 'type', 'unit'];

    public function inputBatches(): HasMany
    {
        return $this->hasMany(InputBatch::class, 'input_id');
    }
}
