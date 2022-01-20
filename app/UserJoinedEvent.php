<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserJoinedEvent extends Model
{
    protected $table = 'user_joined_events';

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    protected $fillable = [
        'user_id',
        'event_id',
        'referee_id',
        'event_specified_id',
        'amount',
        'created_at',
        'updated_at'
    ];
}
