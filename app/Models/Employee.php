<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'first_name',
        'middle_name',
        'last_name',
        'sex_id',
        'suffix_id',
        'division_id',
        'unit_id',
        'office_id',
        'professional_image',
        'profile_image'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'full_name',
    ];


    /**
     * Get the unit
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    /**
     * Get the office
     */
    public function office(): BelongsTo
    {
        return $this->belongsTo(Office::class);
    }

    /**
     * Get the user account associated with this employee
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }

    /**
     * Get full name attribute
     */
    public function getFullNameAttribute(): string
    {
        $name = trim("{$this->first_name} {$this->middle_name} {$this->last_name}");
        return $this->suffix ? "{$name} {$this->suffix}" : $name;
    }

    /**
     * Get all activities for the employee.
     */
    public function employeeActivities()
    {
        return $this->hasMany(EmployeeActivity::class, 'employee_id');
    }

    /**
     * Scope a query to only include employees with activities in the employee_activities table of a given status.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithActivityStatus($query, $status)
    {
        return $query->whereHas('employeeActivities', function ($q) use ($status) {
            $q->where('status', $status);
        });
    }

    /**
     * Get all pending activities from the employee_activities table for this employee.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pendingEmployeeActivities()
    {
        return $this->employeeActivities()->where('status', 'pending');
    }

    /**
     * Get all in progress activities from the employee_activities table for this employee.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function inProgressEmployeeActivities()
    {
        return $this->employeeActivities()->where('status', 'in_progress');
    }

    /**
     * Get all finished activities from the employee_activities table for this employee.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function finishedEmployeeActivities()
    {
        return $this->employeeActivities()->where('status', 'finished');
    }

    /**
     * Get all cancelled activities from the employee_activities table for this employee.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cancelledEmployeeActivities()
    {
        return $this->employeeActivities()->where('status', 'cancelled');
    }
}
