<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeActivity extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'employee_id',
        'assigned_by_id',
        'activity_type_id',
        'mfo_id',
        'description',
        'activity_status_id',
        'remarks',
        'time_spent_minutes',
        'activity_date',
    ];

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

    public function activityStatus()
    {
        return $this->belongsTo(ActivityStatus::class, 'activity_status_id');
    }


    public function assignedBy()
    {
        return $this->belongsTo(Employee::class, 'assigned_by_id');
    }

    public function mfo()
    {
        return $this->belongsTo(MFO::class, 'mfo_id');
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

    public function scopeFilter($query, array $filters)
    {
        return $query->when($filters['search'] ?? null, function ($q, $search) {
            $q->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                    ->orWhereHas('employee', fn($q) => $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('employee_id', 'like', "%{$search}%"))
                    ->orWhereHas('assignedBy', fn($q) => $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%"));
            });
        })->when($filters['activity_status_id'] ?? null, function ($q, $activityStatusId) {
            $q->where('activity_status_id', $activityStatusId);
        })->when($filters['assigned_by_id'] ?? null, function ($q, $assignedBy) {
            $q->where('assigned_by_id', $assignedBy);
        })->when($filters['date_from'] ?? null, function ($q, $from) {
            $q->whereDate('activity_date', '>=', $from);
        })->when($filters['date_to'] ?? null, function ($q, $to) {
            $q->whereDate('activity_date', '<=', $to);
        });
    }

    public function scopeWithRelations($query)
    {
        return $query->with([
            'activityType',
            'assignedBy:id,first_name,middle_name,last_name,suffix_id',
            'activityStatus',
            'mfo',
            'employee:id,first_name,middle_name,last_name,suffix_id',
        ]);
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
