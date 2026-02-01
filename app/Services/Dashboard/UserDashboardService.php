<?php

namespace App\Services\Dashboard;

use App\Models\EmployeeActivity;
use Carbon\Carbon;

class UserDashboardService
{
    public function getDashboardData(int $userId, array $filters): array
    {
        $query = $this->baseQuery($userId, $filters);

        $activities = (clone $query)
            ->with(['activityType', 'assignedBy'])
            ->latest('activity_date')
            ->latest()
            ->get();

        return [
            'stats' => $this->stats($query),
            'recentActivities' => $activities->take(5)->values(),
            'weeklyStats' => $this->weeklyStats($query),
            'assignedTimeSeries' => $this->timeByAssigned($activities),
            'typeTimeSeries' => $this->timeByType($activities),
            'filters' => $filters,
        ];
    }

    protected function baseQuery(int $userId, array $filters)
    {
        return EmployeeActivity::where('employee_id', $userId)
            ->when(
                $filters['date_from'] ?? null,
                fn($q, $from) =>
                $q->whereDate('activity_date', '>=', $from)
            )
            ->when(
                $filters['date_to'] ?? null,
                fn($q, $to) =>
                $q->whereDate('activity_date', '<=', $to)
            );
    }

    protected function stats($query): array
    {
        return [
            'total' => (clone $query)->count(),
            'pending' => (clone $query)->whereStatus('pending')->count(),
            'in_progress' => (clone $query)->whereStatus('in_progress')->count(),
            'finished' => (clone $query)->whereStatus('finished')->count(),
            'cancelled' => (clone $query)->whereStatus('cancelled')->count(),
            'total_time_minutes' => (clone $query)
                ->whereNotNull('time_spent_minutes')
                ->sum('time_spent_minutes'),
        ];
    }

    protected function weeklyStats($query): array
    {
        return (clone $query)
            ->whereBetween('activity_date', [
                now()->startOfWeek(),
                now()->endOfWeek(),
            ])
            ->selectRaw("
                COUNT(*) as total,
                SUM(status = 'finished') as finished
            ")
            ->first()
            ->only(['total', 'finished']);
    }

    protected function timeByAssigned($activities)
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
