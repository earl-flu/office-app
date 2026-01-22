<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        // Get employees that don't already have a user account
        $employees = Employee::whereDoesntHave('user')
            ->orderBy('employee_id')
            ->get();

        return Inertia::render('Auth/Register', [
            'employees' => $employees,
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:' . User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'employee_id' => 'required|exists:employees,id',
            'is_active' => 'sometimes|boolean',
            'is_approved' => 'sometimes|boolean',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'employee_id' => $request->employee_id,
            'is_active' => $request->has('is_active') ? $request->boolean('is_active') : true,
            'is_approved' => $request->has('is_approved') ? $request->boolean('is_approved') : false,
        ]);

        event(new Registered($user));

        // Don't auto-login unapproved users
        // Auth::login($user);

        return redirect(route('login'))
            ->with('status', 'Registration successful! Your account is pending approval from an administrator.');
    }
}
