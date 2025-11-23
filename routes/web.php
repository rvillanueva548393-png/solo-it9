<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;

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

// 1. Login Page (The first screen you see)
Route::get('/', function () {
    return view('login');
})->name('login');

// Handle the Login Form Submission (Redirects to Dashboard)
Route::post('/login', function () {
    return redirect()->route('dashboard');
})->name('login.post');


// 2. Dashboard (Full Employee List)
Route::get('/dashboard', [EmployeeController::class, 'index'])->name('dashboard');


// 3. Register Page (Add New Employee)
Route::get('/register', [EmployeeController::class, 'create'])->name('register');
Route::post('/register', [EmployeeController::class, 'store'])->name('register.store');


// 4. Daily Logs (Attendance History)
Route::get('/logs', [EmployeeController::class, 'logs'])->name('logs');


// 5. Analytics (Donut Chart & Statistics)
Route::get('/attendance-analytics', [EmployeeController::class, 'attendance'])->name('attendance.analytics');


// 6. Activity Logs (Timeline of actions)
Route::get('/activity-logs', [EmployeeController::class, 'activityLogs'])->name('activity.logs');


// 7. Settings (Change Password)
Route::get('/settings', [EmployeeController::class, 'settings'])->name('settings');
Route::post('/settings', [EmployeeController::class, 'updateSettings'])->name('settings.update');