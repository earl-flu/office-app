<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of all users.
     */
    public function index()
    {
        $users = User::with(['employee', 'roles'])
            ->where('is_approved', true)
            ->when(FacadesRequest::input('search'), function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhereHas('employee', function ($employeeQuery) use ($search) {
                            $employeeQuery->where('employee_id', 'like', "%{$search}%")
                                ->orWhere('first_name', 'like', "%{$search}%")
                                ->orWhere('last_name', 'like', "%{$search}%");
                        });
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString()
            ->onEachSide(0);

        // Get all available roles for the dropdown
        $roles = Role::orderBy('name')->get(['id', 'name']);

        return Inertia::render('Users/Index', [
            'users' => $users,
            'roles' => $roles,
            'filters' => FacadesRequest::only('search'),
        ]);
    }

    /**
     * Assign a role to a user
     */
    public function assignRole(Request $request, User $user)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        $role = Role::findOrFail($request->role_id);

        // Check if user already has this role
        if ($user->hasRole($role->name)) {
            return redirect()->route('users.index')
                ->with('error', 'User already has this role.');
        }

        // Assign the role (this allows multiple roles)
        $user->assignRole($role->name);

        return redirect()->route('users.index')
            ->with('success', 'Role assigned successfully.');
    }

    /**
     * Remove a specific role from user
     */
    public function removeRole(Request $request, User $user)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        $role = Role::findOrFail($request->role_id);

        // Remove the specific role
        $user->removeRole($role->name);

        return redirect()->route('users.index')
            ->with('success', 'Role removed successfully.');
    }
}
