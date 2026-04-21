<?php

/**
 * @Author: Anwarul
 * @Date: 2025-12-31 11:31:40
 * @LastEditors: Anwarul
 * @LastEditTime: 2026-04-07 15:51:37
 * @Description: Innova IT
 */

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes;
    protected $guard_name = 'web';
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'status',
        'phone_verified_at',
        'email_verified_at',
        'deleted_at',
        'password',
        'address',
        'gender',
        'dob',
        'profession',
        'profile_image',
        'is_affiliate',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'phone_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function devices()
    {
        return $this->hasMany(UserDevice::class);
    }


    public function walletHistories()
    {
        return $this->hasMany(WalletHistory::class, 'user_id', 'id');
    }

    public function affiliate()
    {
        return $this->belongsTo(Affiliate::class,  'id', 'user_id');
    }

    public function enrolls()
    {
        return $this->hasMany(Enroll::class, 'referral_id');
    }
}
