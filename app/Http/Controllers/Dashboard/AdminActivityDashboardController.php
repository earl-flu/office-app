<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\User;
use App\Services\Dashboard\AdminDashboardService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminActivityDashboardController extends Controller
{
    /**
     * Display the admin activity dashboard with filters and aggregate charts.
     */
    public function index(Request $request, AdminDashboardService $dashboard)
    {
        $filters = $request->only(['date_from', 'date_to', 'employee_id', 'assigned_by_id']);
        $data = $dashboard->getDashboardData($filters);

        // Resolve employee display names for recent activities (employee_id is user id in this app)
        $userIds = collect($data['recentActivities'])->pluck('employee_id')->filter()->unique()->values()->all();
        $usersById = $userIds ? User::whereIn('id', $userIds)->with('employee')->get()->keyBy('id') : collect();
        $data['recentActivities'] = collect($data['recentActivities'])->map(function ($activity) use ($usersById) {
            $user = $usersById->get($activity->employee_id);
            $activity->setAttribute('employee_display_name', $user?->employee?->full_name ?? $user?->name ?? $user?->email ?? '—');
            return $activity;
        })->values()->all();

        // Users for "employee" filter (employee_id in activities is user id)
        $users = User::query()
            ->orderBy('name')
            ->get(['id', 'name', 'email'])
            ->map(fn (User $u) => [
                'id' => $u->id,
                'name' => $u->name ?: $u->email,
            ]);

        // Employees for "assigned by" filter
        $employees = Employee::query()
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get(['id', 'first_name', 'last_name', 'employee_id'])
            ->map(fn (Employee $e) => [
                'id' => $e->id,
                'full_name' => $e->full_name,
                'employee_id' => $e->employee_id,
            ]);

        return Inertia::render('AdminActivityDashboard', [
            ...$data,
            'users' => $users,
            'employees' => $employees,
        ]);
    }
}
