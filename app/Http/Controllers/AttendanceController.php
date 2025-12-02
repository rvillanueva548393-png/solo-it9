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
        $action = $request->input('action'); // Get the specific action (checkin, lunch_start, lunch_end, checkout)

        $attendance = Attendance::where('EmployeeID', $employee->EmployeeID)
                                ->where('Date', $today)
                                ->first();

        // 1. CHECK IN
        if (!$attendance && $action == 'checkin') {
            $shift = $employee->shift; 
            $status = 'Present';

            if ($shift) {
                $shiftStart = Carbon::parse($today . ' ' . $shift->StartTime);
                if ($currentTime->gt($shiftStart->addMinutes(15))) {
                    $status = 'Late';
                }
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
        
        // 2. LUNCH START
        elseif ($attendance && $action == 'lunch_start' && $attendance->LunchStart == null) {
            $attendance->update(['LunchStart' => $currentTime->toTimeString()]);
            
            $employee->Status = 'Lunch Break: ' . $currentTime->format('h:i A');
            $employee->save();

            return back()->with('success', 'Lunch Break Started!');
        }

        // 3. LUNCH END
        elseif ($attendance && $action == 'lunch_end' && $attendance->LunchStart != null && $attendance->LunchEnd == null) {
            $attendance->update(['LunchEnd' => $currentTime->toTimeString()]);
            
            // Revert status to Time In (or just "Back from Lunch")
            $employee->Status = 'Time In: ' . Carbon::parse($attendance->CheckInTime)->format('h:i A');
            $employee->save();

            return back()->with('success', 'Lunch Break Ended. Back to Work!');
        }

        // 4. CHECK OUT
        elseif ($attendance && $action == 'checkout' && $attendance->CheckOutTime == null) {
            $attendance->update(['CheckOutTime' => $currentTime->toTimeString()]);

            $employee->Status = 'Time Out: ' . $currentTime->format('h:i A');
            $employee->save();

            return back()->with('success', 'Shift Completed. Checked Out!');
        }

        return back()->with('error', 'Invalid Action or Already Completed.');
    }
}