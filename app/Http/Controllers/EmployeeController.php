<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Department;
use App\Models\Shift;
use App\Models\Attendance;
use App\Models\ActivityLog;
use App\Models\Manager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf; // Import the PDF Library

class EmployeeController extends Controller
{
    // ==========================
    // ADMIN DASHBOARD
    // ==========================

    // 1. Show the Dashboard with Employee List
    public function index()
    {
        $employees = Employee::with('department')->latest()->get();
        return view('dashboard', compact('employees'));
    }

    // ==========================
    // REGISTRATION
    // ==========================

    // 2. Show the Register Form
    public function create()
    {
        $departments = Department::all();
        $shifts = Shift::all();
        return view('register', compact('departments', 'shifts'));
    }

    // 3. Store the New Employee
    public function store(Request $request)
    {
        // Validate Input
        $validated = $request->validate([
            'FirstName' => 'required|string|max:255',
            'MiddleName' => 'nullable|string|max:255',
            'LastName' => 'required|string|max:255',
            'Age' => 'nullable|integer',
            'ContactNumber' => 'required|string',
            'Email' => 'required|email|unique:employees,Email',
            'password' => 'required|string|min:6',
            'Address' => 'required|string',
            'Photo' => 'nullable|image|max:2048',
            'DepartmentID' => 'nullable|exists:departments,DepartmentID',
            'ShiftID' => 'nullable|exists:shifts,ShiftID',
        ]);

        // Handle Photo Upload
        if ($request->hasFile('Photo')) {
            $path = $request->file('Photo')->store('photos', 'public');
            $validated['Photo'] = $path;
        }

        // Hash Password
        $validated['password'] = Hash::make($request->password);

        // Save to Database
        $employee = Employee::create($validated);

        // Create Activity Log
        ActivityLog::create([
            'description' => 'New Employee (' . $employee->FirstName . ') Registered',
        ]);

        return redirect()->route('dashboard')->with('success', 'Employee Registered Successfully!');
    }

    // ==========================
    // EMPLOYEE MANAGEMENT
    // ==========================

    // 4. Show Single Employee Details
    public function show($id)
    {
        $employee = Employee::with(['department', 'shift'])->findOrFail($id);
        return view('employee_details', compact('employee'));
    }

    // 5. Update Employee Details
    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);
        
        $validated = $request->validate([
            'Email' => 'required|email|unique:employees,Email,'.$id.',EmployeeID',
            'ContactNumber' => 'required|string',
            'Age' => 'nullable|integer',
            'Address' => 'required|string',
            'DepartmentID' => 'required|exists:departments,DepartmentID',
            'ShiftID' => 'required|exists:shifts,ShiftID',
        ]);

        $employee->update($validated);

        return back()->with('success', 'Employee Profile Updated Successfully!');
    }

    // 6. Delete Employee
    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        
        // Delete photo from storage if exists
        if ($employee->Photo) {
            Storage::disk('public')->delete($employee->Photo);
        }

        $employee->delete();

        return redirect()->route('dashboard')->with('success', 'Employee Record Deleted Successfully!');
    }

    // ==========================
    // LOGS & ANALYTICS
    // ==========================

    // 7. Show Daily Logs Page
    public function logs()
    {
        $attendances = Attendance::with('employee')->orderBy('Date', 'desc')->get();
        return view('logs', compact('attendances'));
    }

    // 8. Attendance Analytics Page
    public function attendance()
    {
        $totalEmployees = Employee::count();
        
        $present = Attendance::whereDate('Date', Carbon::today())
                             ->where('Status', 'Present')
                             ->count();
                             
        $late = Attendance::whereDate('Date', Carbon::today())
                          ->where('Status', 'Late')
                          ->count();

        $loggedIds = Attendance::whereDate('Date', Carbon::today())->pluck('EmployeeID');
        $absent = Employee::whereNotIn('EmployeeID', $loggedIds)->count();

        $percentage = $totalEmployees > 0 ? round(($present / $totalEmployees) * 100) : 0;

        return view('attendance_analytics', compact('totalEmployees', 'present', 'late', 'absent', 'percentage'));
    }

    // 9. Activity Logs Page
    public function activityLogs()
    {
        $activities = ActivityLog::orderBy('created_at', 'desc')->get();
        return view('activity_logs', compact('activities'));
    }

    // ==========================
    // SETTINGS
    // ==========================

    // 10. Settings Page
    public function settings()
    {
        return view('settings');
    }

    // 11. Update Settings (Password)
    public function updateSettings(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        // Find Admin (Manager ID 1)
        $admin = Manager::find(1);

        if (!$admin) {
            return back()->withErrors(['msg' => 'Admin account not found.']);
        }

        if (!Hash::check($request->current_password, $admin->password)) {
            return back()->withErrors(['current_password' => 'The provided password does not match your current password.']);
        }

        $admin->password = Hash::make($request->new_password);
        $admin->save();

        return back()->with('success', 'Password updated successfully!');
    }

    // ==========================
    // REPORT PAGE & PDF
    // ==========================

    // 12. Show Report Page
    public function report()
    {
        // Calculate Statistics for Today
        $totalEmployees = Employee::count();
        
        // Count based on INITIAL status (Present/Late) to preserve count after checkout
        $onTime = Attendance::whereDate('Date', Carbon::today())
                            ->where('Status', 'Present')
                            ->count();
                            
        $late = Attendance::whereDate('Date', Carbon::today())
                         ->where('Status', 'Late')
                         ->count();
                         
        // Calculate Absent
        $presentCount = Attendance::whereDate('Date', Carbon::today())->count();
        $onLeave = $totalEmployees - $presentCount; 

        // Get recent activity logs for the report list
        $recentActivities = ActivityLog::latest()->take(5)->get();

        return view('report', compact('totalEmployees', 'onTime', 'late', 'onLeave', 'recentActivities'));
    }

    // 13. Download Report PDF
    public function downloadReport()
    {
        // 1. Fetch the exact same data as the report page
        $totalEmployees = Employee::count();
        
        $onTime = Attendance::whereDate('Date', Carbon::today())
                            ->where('Status', 'Present')
                            ->count();
                            
        $late = Attendance::whereDate('Date', Carbon::today())
                         ->where('Status', 'Late')
                         ->count();
                         
        $presentCount = Attendance::whereDate('Date', Carbon::today())->count();
        $onLeave = $totalEmployees - $presentCount; 

        $recentActivities = ActivityLog::latest()->take(20)->get(); // Get more rows for PDF

        // 2. Load the PDF View
        $pdf = Pdf::loadView('report_pdf', compact('totalEmployees', 'onTime', 'late', 'onLeave', 'recentActivities'));

        // 3. Download the file
        return $pdf->download('DJLN_Report_' . Carbon::now()->format('Y-m-d') . '.pdf');
    }
}