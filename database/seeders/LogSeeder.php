<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\Attendance;
use Carbon\Carbon;

class LogSeeder extends Seeder
{
    public function run()
    {
        // 1. Find the first employee in your database (Joana)
        $employee = Employee::first();

        if (!$employee) {
            $this->command->error("No employees found! Please register an employee first.");
            return;
        }

        // 2. Clear old broken logs (Optional: Keeps it clean)
        Attendance::truncate();

        // 3. Create a 'Time In' record for this employee TODAY
        Attendance::create([
            'EmployeeID' => $employee->EmployeeID, // This links it to Joana!
            'Date' => Carbon::now()->toDateString(),
            'CheckInTime' => '08:00:00',
            'CheckOutTime' => null, // She hasn't timed out yet
            'Status' => 'Present'
        ]);

        $this->command->info("Success! Created a log for: " . $employee->FirstName);
    }
}