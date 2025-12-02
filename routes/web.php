<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AttendanceController; // Don't forget to import this at the top!

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// ==============================
// 1. AUTHENTICATION ROUTES
// ==============================

// Show Login Page (Default Screen)
Route::get('/', [AuthController::class, 'showLogin'])->name('login');

// Handle Login Form Submission
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// Handle Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// ==============================
// 2. ADMIN ROUTES (Protected: Only Managers)
// ==============================
// Using 'auth:web' ensures ONLY users logged in via the 'web' guard (Admins) can enter.

Route::group(['middleware' => ['auth:web']], function () {
    
    // Dashboard
    Route::get('/dashboard', [EmployeeController::class, 'index'])->name('dashboard');

    // Register
    Route::get('/register', [EmployeeController::class, 'create'])->name('register');
    Route::post('/register', [EmployeeController::class, 'store'])->name('register.store');

    // Logs
    Route::get('/logs', [EmployeeController::class, 'logs'])->name('logs');

    // Analytics
    Route::get('/attendance-analytics', [EmployeeController::class, 'attendance'])->name('attendance.analytics');

    // Activity Logs
    Route::get('/activity-logs', [EmployeeController::class, 'activityLogs'])->name('activity.logs');

    // Settings
    Route::get('/settings', [EmployeeController::class, 'settings'])->name('settings');
    Route::post('/settings', [EmployeeController::class, 'updateSettings'])->name('settings.update');

    // Employee Details & Management
    Route::get('/employees/{id}', [EmployeeController::class, 'show'])->name('employees.show');
    Route::put('/employees/{id}', [EmployeeController::class, 'update'])->name('employees.update');
    Route::delete('/employees/{id}', [EmployeeController::class, 'destroy'])->name('employees.destroy');

    // Report Page
    Route::get('/report', [EmployeeController::class, 'report'])->name('report');
    
    // Report PDF Download
    Route::get('/report/download', [EmployeeController::class, 'downloadReport'])->name('report.download');

});


// ==============================
// 3. EMPLOYEE ROUTES (Protected: Only Employees)
// ==============================
// Using 'auth:employee' ensures ONLY users logged in via the 'employee' guard can enter.

Route::group(['middleware' => ['auth:employee']], function () {

    // Employee Portal (Simple Status View)
    Route::get('/employee/dashboard', [AuthController::class, 'employeeDashboard'])->name('employee.dashboard');
    
    // Attendance Action Route (Check In/Lunch/Clock Out)
    // This route will handle the form submission from the buttons
    Route::post('/employee/attendance/update', [AttendanceController::class, 'updateStatus'])->name('attendance.update');

});