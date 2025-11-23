<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Department;
use App\Models\Shift;
use App\Models\Attendance;
use App\Models\ActivityLog; // Imported the new ActivityLog model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class EmployeeController extends Controller
{
    // 1. Show the Dashboard with Employee List
    public function index()
    {
        // Get employees with their Department info, newest first
        $employees = Employee::with('department')->latest()->get();
        return view('dashboard', compact('employees'));
    }

    // 2. Show the Register Form
    public function create()
    {
        $departments = Department::all();
        $shifts = Shift::all();
        return view('register', compact('departments', 'shifts'));
    }

    // 3. Store the New Employee (With Photo Upload)
    public function store(Request $request)
    {
        // A. Validate the incoming data
        $validated = $request->validate([
            'FirstName' => 'required|string|max:255',
            'MiddleName' => 'nullable|string|max:255',
            'LastName' => 'required|string|max:255',
            'Age' => 'nullable|integer',
            'ContactNumber' => 'required|string',
            'Address' => 'required|string',
            'Photo' => 'nullable|image|max:2048', // Max 2MB image
            // Foreign keys
            'DepartmentID' => 'nullable|exists:departments,DepartmentID',
            'ShiftID' => 'nullable|exists:shifts,ShiftID',
        ]);

        // B. Handle File Upload (Face Picture)
        if ($request->hasFile('Photo')) {
            // Save to 'storage/app/public/photos' and get the filename
            $path = $request->file('Photo')->store('photos', 'public');
            $validated['Photo'] = $path;
        }

        // C. Save to Database
        $employee = Employee::create($validated);

        // D. Create an Activity Log entry (Optional but good for your timeline)
        ActivityLog::create([
            'description' => 'New Employee (' . $employee->FirstName . ') Registered',
        ]);

        // E. Redirect back to Dashboard
        return redirect()->route('dashboard')->with('success', 'Employee Registered Successfully!');
    }

    // 4. Show Daily Logs Page
        public function logs()
    {
        // Fetch attendances with the related employee data, ordered by date
        $attendances = Attendance::with('employee')->orderBy('Date', 'desc')->get();
        
        return view('logs', compact('attendances'));
    }
    

    // 5. Attendance Analytics Page (Donut Chart)
    public function attendance()
    {
        // 1. Get Totals for TODAY
        $totalEmployees = Employee::count();
        
        $present = Attendance::whereDate('Date', Carbon::today())
                             ->where('Status', 'Present')
                             ->count();
                             
        $late = Attendance::whereDate('Date', Carbon::today())
                          ->where('Status', 'Late')
                          ->count();

        // Check if any employees are not logged yet (Assume Absent)
        // This logic finds employees who DO NOT have an attendance record for today
        $loggedIds = Attendance::whereDate('Date', Carbon::today())->pluck('EmployeeID');
        $absent = Employee::whereNotIn('EmployeeID', $loggedIds)->count();

        // 2. Calculate Percentage for the Graph
        // Avoid division by zero
        $percentage = $totalEmployees > 0 ? round(($present / $totalEmployees) * 100) : 0;

        return view('attendance_analytics', compact('totalEmployees', 'present', 'late', 'absent', 'percentage'));
    }
    

    // 6. NEW: Activity Logs Page (Timeline)
    public function activityLogs()
    {
        // Get logs, newest first
        $activities = ActivityLog::orderBy('created_at', 'desc')->get();
        
        return view('activity_logs', compact('activities'));
    }
}