<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_description',
        'assigned_by_employee_id',
        'assigned_to_user_id',
        'time_spent_minutes',
        'started_at',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    /**
     * Get the employee who assigned this task
     */
    public function assignedByEmployee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'assigned_by_employee_id');
    }

    /**
     * Get the user/employee who performs this task
     */
    public function assignedToUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to_user_id');
    }
}
