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
        //
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
