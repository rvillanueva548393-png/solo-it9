<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Manager;
use App\Models\Employee;

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

        // 1. Admin Login
        if (Auth::guard('web')->attempt(['Email' => $request->email, 'password' => $request->password])) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

        // 2. Employee Login
        if (Auth::guard('employee')->attempt(['Email' => $request->email, 'password' => $request->password])) {
            $request->session()->regenerate();
            
            // NOTE: We REMOVED the automatic attendance logic here.
            // Employees must now click the button on their dashboard to clock in.

            return redirect()->intended(route('employee.dashboard'));
        }

        return back()->withErrors(['email' => 'Invalid credentials.']);
    }

    // Handle Logout
    public function logout()
    {
        if(Auth::guard('web')->check()) Auth::guard('web')->logout();
        if(Auth::guard('employee')->check()) Auth::guard('employee')->logout();

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