<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VipMemberships extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'image', 'url','store_id','deleted_at','updated_at','created_at'];
}
