<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeActivities extends Model
{
    use HasFactory;

    /**
     * Get the activity type that this employee activity belongs to.
     */
    public function activityType()
    {
        return $this->belongsTo(ActivityTypes::class, 'activity_type_id');
    }

    /**
     * Get the employee that this activity belongs to.
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    /**
     * Scope a query to only include activities of a given status.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to only include activities for a given employee.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $employeeId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForEmployee($query, $employeeId)
    {
        return $query->where('employee_id', $employeeId);
    }

    /**
     * Set the description attribute and trim extra whitespace.
     *
     * @param string $value
     */
    public function setDescriptionAttribute($value)
    {
        $this->attributes['description'] = trim($value);
    }

    /**
     * Get a short version of the description.
     *
     * @return string
     */
    public function getShortDescriptionAttribute()
    {
        return \Str::limit($this->description, 50);
    }

    /**
     * Check if activity is pending
     *
     * @return bool
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if activity is in progress
     *
     * @return bool
     */
    public function isInProgress(): bool
    {
        return $this->status === 'in_progress';
    }

    /**
     * Check if activity is finished
     *
     * @return bool
     */
    public function isFinished(): bool
    {
        return $this->status === 'finished';
    }

    /**
     * Check if activity is cancelled
     *
     * @return bool
     */
    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }
}
