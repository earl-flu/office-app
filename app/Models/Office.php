<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'abbreviation',
        'facility_type_id',
    ];

    public function papers()
    {
        return $this->hasMany(Paper::class);
    }

    /**
     * Get the facility type
     */
    public function facilityType(): BelongsTo
    {
        return $this->belongsTo(FacilityType::class);
    }
}
