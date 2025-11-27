<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Employee;
use App\Models\Attendance;
use Carbon\Carbon;

class FixLogsSeeder extends Seeder
{
    public function run()
    {
        // 1. Delete ALL existing attendance logs (Start Fresh)
        Attendance::truncate();
        $this->command->info('All old/broken logs deleted.');

        // 2. Get all real employees
        $employees = Employee::all();

        if ($employees->isEmpty()) {
            $this->command->error('No employees found! Please register some employees first.');
            return;
        }

        // 3. Create a dummy log for EACH real employee for TODAY
        foreach ($employees as $employee) {
            Attendance::create([
                'EmployeeID' => $employee->EmployeeID, // This connects it correctly!
                'Date' => Carbon::today(),
                'CheckInTime' => '08:00:00',
                'CheckOutTime' => null, // Not checked out yet
                'Status' => 'Present'
            ]);
        }

        $this->command->info('Created ' . $employees->count() . ' valid attendance logs.');
    }
}