<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id('EmployeeID');
            // Updated fields to match your design
            $table->string('FirstName');
            $table->string('MiddleName')->nullable();
            $table->string('LastName');
            $table->integer('Age')->nullable();
            $table->string('ContactNumber');
            $table->text('Address');
            $table->string('Photo')->nullable(); // For the Face Picture
            
            // Standard fields
            $table->string('Email')->unique()->nullable(); // Made nullable if not in form
            $table->string('Status')->default('Active');
            
            // Foreign Keys
            $table->unsignedBigInteger('DepartmentID')->nullable();
            $table->unsignedBigInteger('ShiftID')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};