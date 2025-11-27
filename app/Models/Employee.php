<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; // IMPORTANT CHANGE
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Employee extends Authenticatable // Changed from Model to Authenticatable
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'EmployeeID';
    
    // Allow password and other fields to be filled
    protected $fillable = [
        'FirstName', 
        'MiddleName', 
        'LastName', 
        'Age', 
        'ContactNumber', 
        'Address', 
        'Photo', 
        'DepartmentID', 
        'ShiftID', 
        'Status', 
        'Email', 
        'Phone', 
        'password'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    // Relationships
    public function department() {
        return $this->belongsTo(Department::class, 'DepartmentID');
    }

    public function shift() {
        return $this->belongsTo(Shift::class, 'ShiftID');
    }

    public function attendances() {
        return $this->hasMany(Attendance::class, 'EmployeeID');
    }
}