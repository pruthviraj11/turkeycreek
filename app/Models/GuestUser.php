<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuestUser extends Model
{
    use HasFactory;

    protected $table = 'guest_users';
    protected $fillable = [
        'organization', 'contact_person', 'phone_number', 'email', 'address', 'location'
    ];
}
