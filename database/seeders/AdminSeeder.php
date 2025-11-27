<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Manager; // Use the Manager model if available, or DB facade

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // We use the DB facade or Manager model to force update the record.
        // This ensures that if the admin already exists, their password is RESET to the correct hash.
        
        // Option 1: Using DB Facade (Safest if Model has issues)
        DB::table('managers')->updateOrInsert(
            ['Email' => 'admin@djln.com'], // Search condition
            [
                'Name' => 'System Administrator',
                'Phone' => '09123456789',
                // IMPORTANT: Always use Hash::make() for passwords
                'password' => Hash::make('glow1325'), 
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        
        $this->command->info('Admin Account Created/Updated Successfully!');
        $this->command->info('Login Email: admin@djln.com');
        $this->command->info('Login Password: glow1325');
    }
}