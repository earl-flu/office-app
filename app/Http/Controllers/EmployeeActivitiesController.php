<?php

namespace App\Http\Controllers;

use App\Models\EmployeeActivities;
use App\Models\Employee;
use App\Models\ActivityTypes;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EmployeeActivitiesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $perPage = request()->get('per_page', 10);

        $query = EmployeeActivities::with(['activityType', 'employee', 'assignedBy']);

        if ($status = request()->get('status')) {
            $query->where('status', $status);
        }

        if ($employeeId = request()->get('employee_id')) {
            $query->where('employee_id', $employeeId);
        }

        if ($assignedBy = request()->get('assigned_by_id')) {
            $query->where('assigned_by_id', $assignedBy);
        }

        if ($search = request()->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                    ->orWhereHas('employee', function ($q2) use ($search) {
                        $q2->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('employee_id', 'like', "%{$search}%");
                    })
                    ->orWhereHas('assignedBy', function ($q3) use ($search) {
                        $q3->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%");
                    });
            });
        }

        if ($from = request()->get('date_from')) {
            $query->whereDate('activity_date', '>=', $from);
        }

        if ($to = request()->get('date_to')) {
            $query->whereDate('activity_date', '<=', $to);
        }

        $activities = $query->orderBy('activity_date', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        return Inertia::render('EmployeeActivities/Index', [
            'activities' => $activities,
            'filters' => request()->only(['search', 'status', 'employee_id', 'assigned_by_id', 'date_from', 'date_to', 'per_page']),
            'employees' => Employee::orderBy('last_name')->orderBy('first_name')->get(),
            'activityTypes' => ActivityTypes::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('EmployeeActivities/Create', [
            'employees' => Employee::orderBy('last_name')->orderBy('first_name')->get(),
            'activityTypes' => ActivityTypes::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'assigned_by_id' => 'required|exists:employees,id',
            'activity_type_id' => 'required|exists:activity_types,id',
            'description' => 'required|string',
            'status' => 'required|in:pending,in_progress,finished,cancelled',
            'activity_date' => 'required|date',
            'remarks' => 'nullable|string',
            'time_spent_minutes' => 'nullable|integer|min:0',
        ]);

        $validated['employee_id'] = $request->user()->id;

        EmployeeActivities::create($validated);

        return redirect()->route('employee-activities.index')
            ->with('success', 'Employee activity created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(EmployeeActivities $employeeActivities)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EmployeeActivities $employeeActivities)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EmployeeActivities $employeeActivities)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmployeeActivities $employeeActivities)
    {
        //
    }
}
