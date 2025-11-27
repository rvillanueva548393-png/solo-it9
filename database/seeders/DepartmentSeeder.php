<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentSeeder extends Seeder
{
    public function run()
    {
        // 1. Clear old data to avoid duplicates
        DB::table('departments')->truncate();
        DB::table('shifts')->truncate();

        // 2. Add Departments
        $departments = [
            'Production/Manufacturing',
            'Sales and Marketing',
            'Supply Chain',
            
        ];

        foreach ($departments as $dept) {
            DB::table('departments')->insert([
                'DepartmentName' => $dept,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 3. Add Shifts
        $shifts = [
            ['type' => 'Morning Shift', 'start' => '06:00:00', 'end' => '14:00:00'],
            ['type' => 'Regular Shift', 'start' => '08:00:00', 'end' => '17:00:00'],
            ['type' => 'Night Shift',   'start' => '22:00:00', 'end' => '06:00:00'],
        ];

        foreach ($shifts as $shift) {
            DB::table('shifts')->insert([
                'ShiftType' => $shift['type'],
                'StartTime' => $shift['start'],
                'EndTime' => $shift['end'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}