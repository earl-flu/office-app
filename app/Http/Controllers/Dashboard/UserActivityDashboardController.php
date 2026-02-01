<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Services\Dashboard\UserDashboardService;

class UserActivityDashboardController extends Controller
{
    /**
     * Display the user dashboard with their activity statistics and recent activities.
     */
    public function index(Request $request, UserDashboardService $dashboard)
    {
        $data = $dashboard->getDashboardData(
            auth()->id(),
            $request->only(['date_from', 'date_to'])
        );

        return Inertia::render('UserDashboard', $data);
    }
}
