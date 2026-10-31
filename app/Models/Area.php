<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Area extends Model
{
    protected $fillable = [
        'name',
        'description',
        'administrative_region',
    ];

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

}
