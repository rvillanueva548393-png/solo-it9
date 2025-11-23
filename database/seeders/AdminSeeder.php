<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Check if admin exists based on Email
        $exists = DB::table('managers')->where('Email', 'admin@djln.com')->exists();

        // 2. Only insert if it DOES NOT exist
        if (!$exists) {
            DB::table('managers')->insert([
                'Name' => 'System Administrator',
                'Email' => 'admin@djln.com',
                'Phone' => '09123456789',
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            $this->command->info('Admin Account Created Successfully!');
        } else {
            $this->command->info('Admin Account Already Exists (Skipping).');
        }
    }
}