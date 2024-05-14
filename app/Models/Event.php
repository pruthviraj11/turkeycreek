<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'date', 'description', 'image', 'status', 'location', 'learn_more', 'startTime', 'endTime', 'google_map_link'];
}
