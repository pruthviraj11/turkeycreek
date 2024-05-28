<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'mobile_no',
        'password',
        'address',
        'zip_code',
        'verify_phone',
        'access_token',
        'refresh_token',
        'expires_at',
        'reset_otp',
        'reset_token_expires_at',
        'notification_token',
        'start_date',
        'end_date',
        'date_of_birth',
        'gender',
        'image',
        'status',
        'payment_status',
        'last_logged_In',
        'stripe_id',
        'subscription_id',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function pushNotifications()
    {
        return $this->belongsToMany(PushNotification::class, 'push_notification_user');
    }
}
