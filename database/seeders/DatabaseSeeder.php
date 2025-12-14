<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            DepartmentSeeder::class,  // <--- MOVE THIS TO THE TOP
            AdminSeeder::class,       // Runs second, so it can find departments
            ActivityLogSeeder::class,
            FixLogsSeeder::class,
            LogSeeder::class,
        ]);
    }
}