<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id('AttendanceID');
            
            // THIS IS THE CRITICAL MISSING COLUMN
            $table->date('Date'); 
            
            $table->time('CheckInTime')->nullable();
            $table->time('CheckOutTime')->nullable();
            $table->string('Status'); // e.g., 'Present', 'Late', 'Absent'
            
            // Foreign Key
            $table->unsignedBigInteger('EmployeeID');
            $table->foreign('EmployeeID')->references('EmployeeID')->on('employees')->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};