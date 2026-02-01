<?php

namespace App\Services\Dashboard;

use App\Models\EmployeeActivity;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class AdminDashboardService
{
    /**
     * Return admin dashboard data with optional filters: date_from, date_to, employee_id (user id), assigned_by_id (employee id).
     */
    public function getDashboardData(array $filters): array
    {
        $query = $this->baseQuery($filters);

        $activities = (clone $query)
            ->with(['activityType', 'employee', 'assignedBy'])
            ->latest('activity_date')
            ->latest()
            ->get();

        return [
            'stats' => $this->stats($query),
            'recentActivities' => $activities->take(10)->values(),
            'timeByEmployeeSeries' => $this->timeByEmployee($activities),
            'timeByAssignedBySeries' => $this->timeByAssignedBy($activities),
            'typeTimeSeries' => $this->timeByType($activities),
            'filters' => $filters,
        ];
    }

    protected function baseQuery(array $filters): Builder
    {
        return EmployeeActivity::query()
            ->when($filters['date_from'] ?? null, fn(Builder $q, $from) => $q->whereDate('activity_date', '>=', $from))
            ->when($filters['date_to'] ?? null, fn(Builder $q, $to) => $q->whereDate('activity_date', '<=', $to))
            ->when($filters['employee_id'] ?? null, fn(Builder $q, $id) => $q->where('employee_id', $id))
            ->when($filters['assigned_by_id'] ?? null, fn(Builder $q, $id) => $q->where('assigned_by_id', $id));
    }

    protected function stats(Builder $query): array
    {
        return [
            'total' => (clone $query)->count(),
            'pending' => (clone $query)->where('status', 'pending')->count(),
            'in_progress' => (clone $query)->where('status', 'in_progress')->count(),
            'finished' => (clone $query)->where('status', 'finished')->count(),
            'cancelled' => (clone $query)->where('status', 'cancelled')->count(),
            'total_time_minutes' => (clone $query)->whereNotNull('time_spent_minutes')->sum('time_spent_minutes'),
        ];
    }

    /** Top employees by time spent (employee_id in activities is stored as user id in this app). */
    protected function timeByEmployee($activities)
    {
        $grouped = $activities
            ->whereNotNull('time_spent_minutes')
            ->groupBy('employee_id');

        $userIds = $grouped->keys()->filter()->values()->all();
        $users = $userIds ? User::whereIn('id', $userIds)->with('employee')->get()->keyBy('id') : collect();

        return $grouped
            ->map(fn($group) => [
                'label' => $this->userDisplayName($users->get($group->first()->employee_id)),
                'minutes' => (int) $group->sum('time_spent_minutes'),
            ])
            ->sortByDesc('minutes')
            ->values();
    }

    private function userDisplayName(?User $user): string
    {
        if (! $user) {
            return 'Unknown';
        }
        if ($user->relationLoaded('employee') && $user->employee) {
            return $user->employee->full_name;
        }
        return $user->name ?? $user->email ?? 'Unknown';
    }

    protected function timeByAssignedBy($activities)
    {
        return $activities
            ->whereNotNull('time_spent_minutes')
            ->groupBy('assigned_by_id')
            ->map(fn($group) => [
                'label' => optional($group->first()->assignedBy)->full_name ?? 'Unassigned',
                'minutes' => (int) $group->sum('time_spent_minutes'),
            ])
            ->values();
    }

    protected function timeByType($activities)
    {
        return $activities
            ->whereNotNull('time_spent_minutes')
            ->groupBy('activity_type_id')
            ->map(fn($group) => [
                'label' => optional($group->first()->activityType)->name ?? 'Uncategorized',
                'minutes' => (int) $group->sum('time_spent_minutes'),
            ])
            ->values();
    }
}
