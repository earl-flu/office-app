<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityStatus extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'description',
        'is_active',
    ];

    /**
     * Get the employee activities that have this status.
     */
    public function employeeActivities()
    {
        return $this->hasMany(EmployeeActivity::class, 'activity_status_id');
    }
}
