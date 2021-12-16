<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventPayment extends Model
{

    protected $table = 'event_payments';

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'event_id',
        'user_id',
        'amount',
        'transaction_status',
        'created_at',
        'updated_at',
    ];
}
