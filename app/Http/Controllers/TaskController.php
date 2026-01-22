<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Request as FacadesRequest;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = Task::with(['assignedByEmployee', 'assignedToUser.employee'])
            ->when(FacadesRequest::input('search'), function ($query, $search) {
                $query->where('task_description', 'like', "%{$search}%");
            })
            ->when(FacadesRequest::input('user_id'), function ($query, $userId) {
                $query->where('assigned_to_user_id', $userId);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString()
            ->onEachSide(0);

        return Inertia::render('Tasks/Index', [
            'tasks' => $tasks,
            'filters' => FacadesRequest::only('search', 'user_id')
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Tasks/Create', [
            'employees' => Employee::orderBy('employee_id')->get(),
            'users' => User::with('employee')->where('is_approved', true)->orderBy('first_name')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'task_description' => 'required|string',
            'assigned_by_employee_id' => 'nullable|exists:employees,id',
            'assigned_to_user_id' => 'required|exists:users,id',
            'time_spent_minutes' => 'nullable|integer|min:0',
            'started_at' => 'nullable|date',
            'completed_at' => 'nullable|date|after_or_equal:started_at',
        ]);

        Task::create($validated);

        return redirect()->route('tasks.index')
            ->with('success', 'Task created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        $task->load(['assignedByEmployee', 'assignedToUser.employee']);
        
        return Inertia::render('Tasks/Show', [
            'task' => $task,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        return Inertia::render('Tasks/Edit', [
            'task' => $task,
            'employees' => Employee::orderBy('employee_id')->get(),
            'users' => User::with('employee')->where('is_approved', true)->orderBy('first_name')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'task_description' => 'required|string',
            'assigned_by_employee_id' => 'nullable|exists:employees,id',
            'assigned_to_user_id' => 'required|exists:users,id',
            'time_spent_minutes' => 'nullable|integer|min:0',
            'started_at' => 'nullable|date',
            'completed_at' => 'nullable|date|after_or_equal:started_at',
        ]);

        $task->update($validated);

        return redirect()->route('tasks.index')
            ->with('success', 'Task updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()->route('tasks.index')
            ->with('success', 'Task deleted successfully.');
    }
}
