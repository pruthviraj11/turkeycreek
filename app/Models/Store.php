<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'address_store',
        'mobile',
        'website_name',
        'photo',
        'category_id',
        'sub_category_id',
        'status'
    ];
}
