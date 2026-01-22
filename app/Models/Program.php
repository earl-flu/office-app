<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Program extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'abbreviation',
        'is_active'
    ];

    /**
     * Get all employees in this program
     */
    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }
}
