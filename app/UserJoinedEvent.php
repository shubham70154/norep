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
        'paypal_status',
        'paypal_transaction_id',
        'paypal_response',
        'device_type',
        'created_at',
        'updated_at'
    ];
}
