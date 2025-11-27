<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    // FIX: Tell Laravel the Primary Key is 'DepartmentID', not 'id'
    protected $primaryKey = 'DepartmentID';
    
    protected $fillable = ['DepartmentName', 'ManagerID'];
}