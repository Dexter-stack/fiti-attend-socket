<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;


class admin extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $guard = 'admin';
    public $table = "admins";
    protected $fillable = [
        'fullname',
        'user_id',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];


    
}
