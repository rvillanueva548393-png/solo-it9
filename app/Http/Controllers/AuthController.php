<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Manager;
use App\Models\Employee;
use App\Models\Attendance; // Import Attendance
use Carbon\Carbon; // Import Carbon for time checks

class AuthController extends Controller
{
    // Show Login Page
    public function showLogin()
    {
        return view('login');
    }

    // Handle Login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // 1. Try to Login as ADMIN (Manager)
        if (Auth::guard('web')->attempt(['Email' => $request->email, 'password' => $request->password])) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

        // 2. Try to Login as EMPLOYEE
        if (Auth::guard('employee')->attempt(['Email' => $request->email, 'password' => $request->password])) {
            $request->session()->regenerate();
            
            // --- ATTENDANCE LOGIC START ---
            $employee = Auth::guard('employee')->user();
            $today = Carbon::today()->toDateString();
            
            // Check if attendance already exists for today
            $existingAttendance = Attendance::where('EmployeeID', $employee->EmployeeID)
                                            ->where('Date', $today)
                                            ->first();

            if (!$existingAttendance) {
                // SCENARIO 1: FIRST LOGIN -> CLOCK IN
                
                // Get Employee Shift
                $shift = $employee->shift; 
                $currentTime = Carbon::now();
                $status = 'Present'; // Default

                // Logic: Check if Late
                if ($shift) {
                    $shiftStart = Carbon::parse($today . ' ' . $shift->StartTime);
                    
                    // Add a grace period (e.g., 15 minutes)
                    if ($currentTime->gt($shiftStart->addMinutes(15))) {
                        $status = 'Late';
                    }
                }

                // Create the Attendance Record (Time In)
                Attendance::create([
                    'EmployeeID' => $employee->EmployeeID,
                    'Date' => $today,
                    'CheckInTime' => $currentTime->toTimeString(),
                    'Status' => $status, // Saves "Present" or "Late" PERMANENTLY for this day
                    'CheckOutTime' => null,
                ]);

                // Update Employee Status for Dashboard (Visual only)
                $employee->Status = 'Time In: ' . $currentTime->format('h:i A');
                $employee->save();

            } else {
                // SCENARIO 2: ALREADY CLOCKED IN -> CLOCK OUT
                // Only clock out if they haven't already clocked out
                if ($existingAttendance->CheckOutTime == null) {
                    
                    $currentTime = Carbon::now();
                    
                    // Update the existing record with Time Out
                    $existingAttendance->update([
                        'CheckOutTime' => $currentTime->toTimeString(),
                        // We DO NOT update status to 'Out' here to preserve 'Present/Late' history
                    ]);

                    // Update Employee Status for Dashboard (Visual only)
                    $employee->Status = 'Time Out: ' . $currentTime->format('h:i A'); 
                    $employee->save();
                }
                // If they login a 3rd time, we just let them in without changing status
            }
            // --- ATTENDANCE LOGIC END ---

            return redirect()->intended(route('employee.dashboard'));
        }

        // Failed
        return back()->withErrors(['email' => 'Invalid credentials.']);
    }

    // Handle Logout
    public function logout()
    {
        if(Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
        }
        
        if(Auth::guard('employee')->check()) {
            Auth::guard('employee')->logout();
        }

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('login');
    }

    // Show Employee Dashboard
    public function employeeDashboard()
    {
        return view('employee_dashboard');
    }
}