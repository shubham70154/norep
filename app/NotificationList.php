<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NotificationList extends Model
{
    use SoftDeletes;

    protected $table = 'notification_lists';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $fillable = [
        'user_id',
        'referee_id',
        'title',
        'message',
        'response',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
