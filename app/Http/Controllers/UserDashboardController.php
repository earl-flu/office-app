<?php

namespace App\Http\Controllers;

use App\Models\EmployeeActivity;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserDashboardController extends Controller
{
    /**
     * Display the user dashboard with their activity statistics and recent activities.
     */
    public function index(Request $request)
    {
        $userId = auth()->id();

        // Date filters for the dashboard
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        // Base query scoped to the authenticated user's activities and optional date range
        $baseQuery = EmployeeActivity::where('employee_id', $userId);

        if ($dateFrom) {
            $baseQuery->whereDate('activity_date', '>=', $dateFrom);
        }

        if ($dateTo) {
            $baseQuery->whereDate('activity_date', '<=', $dateTo);
        }

        // Clone base query for different aggregates
        $stats = [
            'total' => (clone $baseQuery)->count(),
            'pending' => (clone $baseQuery)->where('status', 'pending')->count(),
            'in_progress' => (clone $baseQuery)->where('status', 'in_progress')->count(),
            'finished' => (clone $baseQuery)->where('status', 'finished')->count(),
            'cancelled' => (clone $baseQuery)->where('status', 'cancelled')->count(),
            'total_time_minutes' => (clone $baseQuery)
                ->whereNotNull('time_spent_minutes')
                ->sum('time_spent_minutes'),
        ];

        // Load all activities for the current filter window once for dashboard lists & charts
        $activitiesForWindow = (clone $baseQuery)
            ->with(['activityType', 'assignedBy'])
            ->orderByDesc('activity_date')
            ->orderByDesc('created_at')
            ->get();

        // Recent activities (last 5 from the filtered window)
        $recentActivities = $activitiesForWindow->take(5)->values();

        // Weekly stats (this calendar week, still scoped to the user and date filters)
        $thisWeekStart = now()->startOfWeek();
        $thisWeekEnd = now()->endOfWeek();

        $weeklyQuery = (clone $baseQuery)->whereBetween('activity_date', [$thisWeekStart, $thisWeekEnd]);

        $weeklyStats = [
            'total' => (clone $weeklyQuery)->count(),
            'finished' => (clone $weeklyQuery)->where('status', 'finished')->count(),
        ];

        // Time spent per "Assigned By"
        $assignedTimeSeries = $activitiesForWindow
            ->whereNotNull('time_spent_minutes')
            ->groupBy('assigned_by_id')
            ->map(function ($group) {
                $first = $group->first();

                return [
                    'label' => optional($first->assignedBy)->full_name ?? 'Unassigned',
                    'minutes' => (int) $group->sum('time_spent_minutes'),
                ];
            })
            ->values();

        // Time spent per Activity Type
        $typeTimeSeries = $activitiesForWindow
            ->whereNotNull('time_spent_minutes')
            ->groupBy('activity_type_id')
            ->map(function ($group) {
                $first = $group->first();

                return [
                    'label' => optional($first->activityType)->name ?? 'Uncategorized',
                    'minutes' => (int) $group->sum('time_spent_minutes'),
                ];
            })
            ->values();

        return Inertia::render('Dashboard', [
            'stats' => $stats,
            'recentActivities' => $recentActivities,
            'weeklyStats' => $weeklyStats,
            'filters' => [
                'date_from' => $dateFrom,
                'date_to' => $dateTo,
            ],
            'assignedTimeSeries' => $assignedTimeSeries,
            'typeTimeSeries' => $typeTimeSeries,
        ]);
    }
}
