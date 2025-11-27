<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shifts', function (Blueprint $table) {
            $table->id('ShiftID');
            
            // THESE 3 COLUMNS MUST BE HERE:
            $table->string('ShiftType'); 
            $table->time('StartTime');
            $table->time('EndTime');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shifts');
    }
};