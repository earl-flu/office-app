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
        'office_type_id',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function papers()
    {
        return $this->hasMany(Paper::class);
    }

    /**
     * Get the office type
     */
    public function OfficeType(): BelongsTo
    {
        return $this->belongsTo(OfficeType::class);
    }

    public function test()
    {
        return 'test';
    }
}
