<?php
/**
 * @Author: Anwarul
 * @Date: 2026-01-22 15:35:31
 * @LastEditors: Anwarul
 * @LastEditTime: 2026-01-22 16:58:21
 * @Description: Innova IT
 */

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Admin extends Authenticatable
{
       use HasFactory, Notifiable, HasRoles;


    protected $guard = 'admin';
    protected $guard_name = 'admin';

    protected $fillable = ['name','email','phone','password','status','role_id'];
       protected $hidden = [
        'password',
        'remember_token',
    ];

}