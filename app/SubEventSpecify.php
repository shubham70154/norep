<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubEventSpecify extends Model
{
    protected $table = 'sub_events_specified';

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    protected $fillable = [
        'event_id',
        'sub_event_id',
        'event_specified_id',
        'created_at',
        'updated_at'
    ];
}
