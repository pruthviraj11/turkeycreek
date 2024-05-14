<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    use HasFactory;

    protected $fillable = ['first_name', 'last_name', 'email', 'mobile', 'password', 'is_active', 'photo', 'verify_phone', 'access_token', 'refresh_token', 'expires_at', 'created_at', 'updated_at'];
}
