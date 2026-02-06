<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'description',
        'is_active',
    ];

    public function mfoCategory()
    {
        return $this->belongsTo(MfoCategory::class);
    }
}
