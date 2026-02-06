<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\Employee;
use App\Models\Facility;
use App\Models\Office;
use App\Models\Program;
use App\Models\Sex;
use App\Models\Suffix;
use App\Models\Unit;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employees = Employee::with(['unit', 'office.officeType', 'division', 'sex'])
            ->when(FacadesRequest::input('search'), function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('employee_id', 'like', "%{$search}%");
                });
            })
            ->orderBy('employee_id')
            ->paginate(10)
            ->withQueryString()
            ->onEachSide(0);

        return Inertia::render('Employees/Index', [
            'employees' => $employees,
            'filters' => FacadesRequest::only('search')
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Employees/Create', [
            'units' => Unit::where('is_active', true)->orderBy('name')->get(),
            'offices' => Office::where('is_active', true)->with('officeType')->orderBy('name')->get(),
            'divisions' => Division::where('is_active', true)->orderBy('name')->get(),
            'suffixes' => Suffix::where('is_active', true)->orderBy('name')->get(),
            'sexes' => Sex::where('is_active', true)->orderBy('description')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
            'employee_id' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'sex_id' => 'required|exists:sexes,id',
            'suffix_id' => 'nullable|exists:suffixes,id',
            'division_id' => 'nullable|exists:divisions,id',
            'unit_id' => 'nullable|exists:units,id',
            'office_id' => 'nullable|exists:offices,id',
            'professional_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('profile_image')) {
            $validated['profile_image'] = $request->file('profile_image')->store('employees', 'public');
        }
        if ($request->hasFile('professional_image')) {
            $validated['professional_image'] = $request->file('professional_image')->store('employees', 'public');
        }

        Employee::create($validated);

        return redirect()->route('employees.index')
            ->with('success', 'Employee created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        $employee->load(['unit', 'office.officeType', 'user', 'division']);

        return Inertia::render('Employees/Show', [
            'employee' => $employee,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee)
    {
        return Inertia::render('Employees/Edit', [
            'employee' => $employee,
            'units' => Unit::where('is_active', true)->orderBy('name')->get(),
            'offices' => Office::where('is_active', true)->with('officeType')->orderBy('name')->get(),
            'divisions' => Division::where('is_active', true)->orderBy('name')->get(),
            'suffixes' => Suffix::where('is_active', true)->orderBy('name')->get(),
            'sexes' => Sex::where('is_active', true)->orderBy('description')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'sex_id' => 'nullable|exists:sexes,id',
            'suffix_id' => 'nullable|exists:suffixes,id',
            'division_id' => 'nullable|exists:divisions,id',
            'unit_id' => 'nullable|exists:units,id',
            'office_id' => 'nullable|exists:offices,id',
            'professional_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('profile_image')) {
            if ($employee->profile_image) {
                Storage::disk('public')->delete($employee->profile_image);
            }
            $validated['profile_image'] = $request->file('profile_image')->store('employees', 'public');
        }
        if ($request->hasFile('professional_image')) {
            if ($employee->professional_image) {
                Storage::disk('public')->delete($employee->professional_image);
            }
            $validated['professional_image'] = $request->file('professional_image')->store('employees', 'public');
        }

        $employee->update($validated);

        return redirect()->route('employees.index')
            ->with('success', 'Employee updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        // Check if employee has associated user or tasks
        if ($employee->user || $employee->assignedTasks()->count() > 0) {
            return redirect()->route('employees.index')
                ->with('error', 'Cannot delete employee with associated records.');
        }

        $employee->delete();

        return redirect()->route('employees.index')
            ->with('success', 'Employee deleted successfully.');
    }
}
