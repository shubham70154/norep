<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotificationList extends Model
{
    protected $table = 'notification_lists';

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    protected $fillable = [
        'user_id',
        'referee_id',
        'title',
        'message',
        'response',
        'created_at',
        'updated_at'
    ];
}
