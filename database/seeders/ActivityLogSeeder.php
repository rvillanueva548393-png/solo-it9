<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ActivityLogSeeder extends Seeder
{
    public function run()
    {
        // Create dummy logs for the timeline
        $logs = [
            ['description' => 'You have logged in', 'minutes_ago' => 5],
            ['description' => 'New Employee (Juan Cruz) Registered', 'minutes_ago' => 25],
            ['description' => 'You have logged in', 'minutes_ago' => 120],
            ['description' => 'Admin logged out', 'minutes_ago' => 125],
            ['description' => 'You have logged in', 'minutes_ago' => 300],
            ['description' => 'System Backup Completed', 'minutes_ago' => 1440], // 1 day ago
            ['description' => 'Admin logged out', 'minutes_ago' => 1500],
        ];

        foreach ($logs as $log) {
            DB::table('activity_logs')->insert([
                'description' => $log['description'],
                'created_at' => Carbon::now()->subMinutes($log['minutes_ago']),
                'updated_at' => Carbon::now()->subMinutes($log['minutes_ago']),
            ]);
        }
    }
}