<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Request as FacadesRequest;

class UserApprovalController extends Controller
{
    /**
     * Display a listing of pending user approvals.
     */
    public function index()
    {
        $users = User::with('employee')
            ->where('is_approved', false)
            ->when(FacadesRequest::input('search'), function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString()
            ->onEachSide(0);

        return Inertia::render('UserApprovals/Index', [
            'users' => $users,
            'filters' => FacadesRequest::only('search')
        ]);
    }

    /**
     * Approve a user account
     */
    public function approve(User $user)
    {
        $user->update(['is_approved' => true]);

        return redirect()->route('user-approvals.index')
            ->with('success', 'User account approved successfully.');
    }

    /**
     * Reject a user account (delete it)
     */
    public function reject(User $user)
    {
        $user->delete();

        return redirect()->route('user-approvals.index')
            ->with('success', 'User account rejected and deleted.');
    }

    /**
     * Bulk approve users
     */
    public function bulkApprove(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        User::whereIn('id', $request->user_ids)
            ->update(['is_approved' => true]);

        return redirect()->route('user-approvals.index')
            ->with('success', 'Selected users approved successfully.');
    }
}
