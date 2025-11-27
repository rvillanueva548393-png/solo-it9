<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    // FIX: Tell Laravel the Primary Key is 'ShiftID', not 'id'
    protected $primaryKey = 'ShiftID';
    
    protected $fillable = ['ShiftType', 'StartTime', 'EndTime'];
}