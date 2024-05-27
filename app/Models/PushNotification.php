<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PushNotification extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $guarded = [];
    protected $fillable = [
        'notification_title',
        'notification_message_body',
        'sendToAllMembers',
        'notification_type',
        'status_flag',
        'user_id',
        'reference_id',
        'read_status'
    ];

    // Add a read accessor
    public function getReadAttribute($value)
    {
        return $value ? 'read' : 'unread';
    }

    // Add a write accessor
    public function setReadAttribute($value)
    {
        $this->attributes['status_flag'] = $value === 'read';
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'push_notification_user');
    }
}
