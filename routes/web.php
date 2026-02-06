<?php

use App\Http\Controllers\EmployeeActivitiesController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\FacilityTypeController;
use App\Http\Controllers\OfficeController;
use App\Http\Controllers\PaperController;
use App\Http\Controllers\PaperDashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\ThemeController;
use App\Http\Controllers\Dashboard\UserActivityDashboardController;
use App\Http\Controllers\Dashboard\AdminActivityDashboardController;
use App\Http\Controllers\OfficeTypeController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserApprovalController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/time', function () {
    return [
        'app_time' => now()->toDateTimeString(),
        'server_time' => date('Y-m-d H:i:s'),
    ];
});

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/test', function () {
    return Inertia::render('Welcomev2', []);
});


Route::get('/user/activity-dashboard', [UserActivityDashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('user-activity-dashboard');

Route::get('/admin/activity-dashboard', [AdminActivityDashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('admin-activity-dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/papers/data', [PaperDashboardController::class, 'getData'])->name('papers.data');
    Route::get('/offices/data', [PaperDashboardController::class, 'getOfficeData'])->name('offices.data');

    Route::get('/papers/dashboard', [PaperDashboardController::class, 'index'])->name('papers.dashboard');
    Route::resource('papers', PaperController::class);
    Route::resource('offices', OfficeController::class)->except(['destroy']);
    Route::resource('tags', TagController::class)->except(['destroy']);

    // Employee Task Recorder System
    Route::resource('office-types', OfficeTypeController::class)->except(['create', 'show', 'edit', 'destroy']);
    Route::resource('facilities', FacilityController::class)->except(['destroy']);
    Route::resource('units', UnitController::class)->except(['destroy']);
    Route::resource('employees', EmployeeController::class);
    Route::resource('employee-activities', EmployeeActivitiesController::class);
    Route::get('user-approvals', [UserApprovalController::class, 'index'])->name('user-approvals.index');
    Route::post('user-approvals/{user}/approve', [UserApprovalController::class, 'approve'])->name('user-approvals.approve');
    Route::delete('user-approvals/{user}/reject', [UserApprovalController::class, 'reject'])->name('user-approvals.reject');
    Route::post('user-approvals/bulk-approve', [UserApprovalController::class, 'bulkApprove'])->name('user-approvals.bulk-approve');

    // User Account Management
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::post('users/{user}/assign-role', [UserController::class, 'assignRole'])->name('users.assign-role');
    Route::post('users/{user}/remove-role', [UserController::class, 'removeRole'])->name('users.remove-role');
    Route::post('users/{user}/set-status', [UserController::class, 'setStatus'])->name('users.set-status');
});

Route::post('/theme/update', [ThemeController::class, 'update'])->name('theme.update');

require __DIR__ . '/auth.php';
