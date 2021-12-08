<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use SoftDeletes;

    protected $table = 'events';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'event_type_id',
        'user_id',
        'description',
        'start_date',
        'start_time',
        'location',
        'latitude',
        'longitude',
        'end_date',
        'end_time',
        'price',
        'player_limit',
        'status',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function event_types()
    {
        return $this->belongsTo(EventType::class, 'event_type_id');
    }
}
