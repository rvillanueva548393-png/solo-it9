<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('departments', function (Blueprint $table) {
            // Primary Key
            $table->id('DepartmentID'); 
            
            // This is the column causing the error. Make sure it is here!
            $table->string('DepartmentName'); 
            
            // Foreign Key to Manager (Optional/Nullable)
            $table->unsignedBigInteger('ManagerID')->nullable();
            $table->foreign('ManagerID')->references('ManagerID')->on('managers')->onDelete('set null');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};