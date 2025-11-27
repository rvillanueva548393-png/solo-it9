<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. AUTHENTICATION ROUTES
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// 2. ADMIN ROUTES (Protected: Only Managers)
Route::group(['middleware' => ['auth:web']], function () {
    
    Route::get('/dashboard', [EmployeeController::class, 'index'])->name('dashboard');
    
    // Register
    Route::get('/register', [EmployeeController::class, 'create'])->name('register');
    Route::post('/register', [EmployeeController::class, 'store'])->name('register.store');

    // Logs & Analytics
    Route::get('/logs', [EmployeeController::class, 'logs'])->name('logs');
    Route::get('/attendance-analytics', [EmployeeController::class, 'attendance'])->name('attendance.analytics');
    Route::get('/activity-logs', [EmployeeController::class, 'activityLogs'])->name('activity.logs');

    // Settings
    Route::get('/settings', [EmployeeController::class, 'settings'])->name('settings');
    Route::post('/settings', [EmployeeController::class, 'updateSettings'])->name('settings.update');

    // Employee Management
    Route::get('/employees/{id}', [EmployeeController::class, 'show'])->name('employees.show');
    Route::put('/employees/{id}', [EmployeeController::class, 'update'])->name('employees.update');
    Route::delete('/employees/{id}', [EmployeeController::class, 'destroy'])->name('employees.destroy');

    // REPORT PAGE (Make sure this line exists!)
    Route::get('/report', [EmployeeController::class, 'report'])->name('report');

});


// 3. EMPLOYEE ROUTES (Protected: Only Employees)
Route::group(['middleware' => ['auth:employee']], function () {
    Route::get('/employee/dashboard', [AuthController::class, 'employeeDashboard'])->name('employee.dashboard');
});