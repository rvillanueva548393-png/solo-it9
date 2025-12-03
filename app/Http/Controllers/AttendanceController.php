<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function updateStatus(Request $request)
    {
        $employee = Auth::guard('employee')->user();
        $today = Carbon::today()->toDateString();
        $currentTime = Carbon::now();
        $action = $request->input('action');

        $attendance = Attendance::where('EmployeeID', $employee->EmployeeID)
                                ->where('Date', $today)
                                ->first();

        // Get Shift Details
        $shift = $employee->shift;
        $shiftType = $shift ? $shift->ShiftType : 'Regular Shift'; // Default to Regular

        // --- DEFINE SHIFT RULES ---
        // You can move these to the database later, but hardcoding for now as requested.
        
        $shiftRules = [
            'Morning Shift' => [
                'start' => '06:00:00', 'end' => '14:00:00', 'lunch_start' => '10:00:00', 'lunch_end' => '11:00:00'
            ],
            'Regular Shift' => [
                'start' => '08:00:00', 'end' => '17:00:00', 'lunch_start' => '12:00:00', 'lunch_end' => '13:00:00'
            ],
            'Night Shift' => [
                'start' => '22:00:00', 'end' => '06:00:00', 'lunch_start' => '02:00:00', 'lunch_end' => '03:00:00'
            ],
        ];

        // Get rules for current user's shift
        // If shift type doesn't match, fallback to Regular
        $rules = $shiftRules[$shiftType] ?? $shiftRules['Regular Shift'];

        // Parse Times
        // Handle Night Shift crossing midnight? (For simplicity, assuming same day logic or 24h format handling)
        // If Night shift crosses midnight, accurate date handling is complex. 
        // For this example, we'll assume times are on the "active" day or standard logic.
        
        $shiftStart = Carbon::parse($today . ' ' . $rules['start']);
        $shiftEnd = Carbon::parse($today . ' ' . $rules['end']);
        $lunchStart = Carbon::parse($today . ' ' . $rules['lunch_start']);
        $lunchEnd = Carbon::parse($today . ' ' . $rules['lunch_end']);

        // Handle Night Shift Crossing Midnight Logic (If start > end, e.g. 22:00 to 06:00)
        if ($shiftStart->gt($shiftEnd)) {
            // If current time is early morning (e.g. 01:00), it belongs to "yesterday's" shift start
            // Or if we are starting at 22:00, the end is tomorrow.
            // Simplified Logic: Just check time windows loosely for now or strictly if needed.
            // Let's stick to standard day logic first to ensure stability.
            $shiftEnd->addDay();
            $lunchStart->addDay(); // Lunch is usually after midnight for night shift
            $lunchEnd->addDay();
        }


        // =========================================================
        // 1. CHECK IN LOGIC
        // =========================================================
        if (!$attendance && $action == 'checkin') {
            
            // STRICT RULE: Cannot time in BEFORE shift starts (optional) or TOO LATE?
            // User request: "can only time in in there time"
            // Let's allow check-in 1 hour before start, up until shift end.
            
            $allowedLoginStart = $shiftStart->copy()->subHour(); 
            
            if ($currentTime->lt($allowedLoginStart)) {
                return back()->with('error', "Too early! Your shift starts at " . $shiftStart->format('h:i A'));
            }
            if ($currentTime->gt($shiftEnd)) {
                return back()->with('error', "Your shift has already ended!");
            }

            $status = 'Present';
            // Late Check (15 mins grace)
            if ($currentTime->gt($shiftStart->addMinutes(15))) {
                $status = 'Late';
            }

            Attendance::create([
                'EmployeeID' => $employee->EmployeeID,
                'Date' => $today,
                'CheckInTime' => $currentTime->toTimeString(),
                'Status' => $status, 
            ]);

            $employee->Status = 'Time In: ' . $currentTime->format('h:i A');
            $employee->save();

            return back()->with('success', 'Checked In Successfully!');
        } 
        
        // =========================================================
        // 2. LUNCH START LOGIC
        // =========================================================
        elseif ($attendance && $action == 'lunch_start' && $attendance->LunchStart == null) {
            
            // STRICT RULE: Can only start lunch during designated window
            if ($currentTime->lt($lunchStart)) {
                return back()->with('error', "Too early! Your lunch break is at " . $lunchStart->format('h:i A'));
            }
            // Allow starting late lunch? Usually yes. But maybe warn if after lunch end?
            
            $attendance->update(['LunchStart' => $currentTime->toTimeString()]);
            $employee->Status = 'Lunch Break: ' . $currentTime->format('h:i A');
            $employee->save();

            return back()->with('success', 'Lunch Break Started!');
        }

        // =========================================================
        // 3. LUNCH END LOGIC
        // =========================================================
        elseif ($attendance && $action == 'lunch_end' && $attendance->LunchStart != null && $attendance->LunchEnd == null) {
            
            $attendance->update(['LunchEnd' => $currentTime->toTimeString()]);
            $employee->Status = 'Time In: ' . Carbon::parse($attendance->CheckInTime)->format('h:i A');
            $employee->save();

            return back()->with('success', 'Lunch Break Ended.');
        }

        // =========================================================
        // 4. CHECK OUT LOGIC
        // =========================================================
        elseif ($attendance && $action == 'checkout' && $attendance->CheckOutTime == null) {
            
            // STRICT RULE: Can only clock out AFTER shift ends (or maybe 15 mins before?)
            // Let's enforce strict Shift End time.
            if ($currentTime->lt($shiftEnd)) {
                $minutesLeft = $currentTime->diffInMinutes($shiftEnd);
                return back()->with('error', "Too early! Your shift ends at " . $shiftEnd->format('h:i A') . " ($minutesLeft mins left)");
            }

            $attendance->update(['CheckOutTime' => $currentTime->toTimeString()]);
            $employee->Status = 'Time Out: ' . $currentTime->format('h:i A');
            $employee->save();

            return back()->with('success', 'Shift Completed. Checked Out!');
        }

        return back()->with('error', 'Invalid Action or Already Completed.');
    }
}