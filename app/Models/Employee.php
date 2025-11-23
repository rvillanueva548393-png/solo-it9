<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    // Tell Laravel your primary key is not 'id', but 'EmployeeID'
    protected $primaryKey = 'EmployeeID';

    // THIS IS THE FIX:
    // We list every single column that we want to allow saving.
    protected $fillable = [
        'FirstName', 
        'MiddleName', 
        'LastName', 
        'Age', 
        'ContactNumber', 
        'Address', 
        'Photo',  
        'Status',
        'Email',
        'Phone' // Keep generic phone/email just in case
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