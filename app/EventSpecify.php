<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventSpecify extends Model
{
    protected $table = 'event_specified_for';

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    protected $fillable = [
        'title',
        'event_id',
        'created_at',
        'updated_at'
    ];
}
