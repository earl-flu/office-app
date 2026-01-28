<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityTypes extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = ['id'];

    /**
     * Get the employee activities for this activity type.
     */
    public function employeeActivities()
    {
        return $this->hasMany(EmployeeActivities::class, 'activity_type_id');
    }
}
