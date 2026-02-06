<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OfficeType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'abbreviation',
        'is_active'
    ];

    /**
     * Get all facilities of this type
     */
    public function offices(): HasMany
    {
        return $this->hasMany(Office::class);
    }
}
