<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDevice extends Model
{
      protected $fillable = [
        'user_id',
        'device_token',
        'device_name',
        'device_type',
        'platform',
        'ip_address',
        'user_agent',
        'is_active',
        'last_used_at',
    ];
}
