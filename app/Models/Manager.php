<?php

namespace App\Models;

// THIS LINE IS CRITICAL:
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

// MUST EXTEND Authenticatable (NOT Model)
class Manager extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'ManagerID';

    protected $fillable = [
        'Name',
        'Email',
        'Phone',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}