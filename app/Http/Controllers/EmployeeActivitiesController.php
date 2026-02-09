<?php

namespace App\Http\Controllers;

use App\Models\EmployeeActivity;
use App\Models\Employee;
use App\Models\ActivityTypes;
use App\Models\Mfo;
use App\Models\Sex;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class EmployeeActivitiesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // 1. Extract and sanitize filters
        $filters = request()->only(['search', 'status', 'assigned_by_id', 'date_from', 'date_to', 'per_page']);

        // 2. Build the query using the Scope
        $activities = EmployeeActivity::query()
            ->with(['activityType', 'employee', 'assignedBy'])
            ->where('employee_id', auth()->id())
            ->filter($filters) // This calls the scopeFilter() in your Model
            ->latest('activity_date')
            ->paginate(request()->get('per_page', 10))
            ->withQueryString();

        return Inertia::render('EmployeeActivities/Index', [
            'activities' => $activities,
            'filters' => request()->only(['search', 'status', 'assigned_by_id', 'date_from', 'date_to', 'per_page']),
            'employees' => Employee::orderBy('last_name')->orderBy('first_name')->get(),
            'activityTypes' => ActivityTypes::where('is_active', true)->orderBy('name')->get(),
            'mfos' => Mfo::where('is_active', true)->orderBy('description')->get(),
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
            'mfos' => Mfo::where('is_active', true)->orderBy('description')->get(),
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

        EmployeeActivity::create($validated);

        return redirect()->route('employee-activities.index')
            ->with('success', 'Employee activity created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(EmployeeActivity $employeeActivity)
    {
        $employeeActivity->load(['activityType', 'employee', 'assignedBy']);

        return Inertia::render('EmployeeActivities/Show', [
            'activity' => $employeeActivity,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EmployeeActivity $employeeActivity)
    {
        Gate::authorize('update', $employeeActivity);

        $employeeActivity->load(['activityType', 'employee', 'assignedBy']);

        return Inertia::render('EmployeeActivities/Edit', [
            'activity' => $employeeActivity,
            'employees' => Employee::orderBy('last_name')->orderBy('first_name')->get(),
            'activityTypes' => ActivityTypes::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EmployeeActivity $employeeActivity)
    {
        $validated = $request->validate([
            'assigned_by_id' => 'required|exists:employees,id',
            'activity_type_id' => 'required|exists:activity_types,id',
            'description' => 'required|string',
            'status' => 'required|in:pending,in_progress,finished,cancelled',
            'remarks' => 'nullable|string',
            'time_spent_minutes' => 'nullable|integer|min:0',
            'activity_date' => 'required|date',
        ]);

        $employeeActivity->update($validated);

        return redirect()->route('employee-activities.index')
            ->with('success', 'Employee activity updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmployeeActivity $employeeActivity)
    {
        //
    }
}
