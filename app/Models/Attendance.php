<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $primaryKey = 'AttendanceID';
    
    // Ensure 'Date' is in fillable so we can create logs
    // Added 'LunchStart' and 'LunchEnd' based on the migration
    protected $fillable = [
        'EmployeeID', 
        'Date', 
        'CheckInTime', 
        'LunchStart', // Added
        'LunchEnd',   // Added
        'CheckOutTime', 
        'Status'
    ];

    // Relationship to the Employee model
    public function employee() {
        return $this->belongsTo(Employee::class, 'EmployeeID');
    }

    // Relationship to NotificationLog (if you have a NotificationLog model)
    // This allows you to access $attendance->notifications
    public function notifications() {
        return $this->hasMany(NotificationLog::class, 'AttendanceID');
    }
}