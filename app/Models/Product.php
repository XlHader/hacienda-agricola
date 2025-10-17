<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = ['name', 'type', 'unit'];

    public function productBatches(): HasMany
    {
        return $this->hasMany(ProductBatch::class);
    }

    public function qualityStandards(): HasMany
    {
        return $this->hasMany(QualityStandard::class);
    }
}
