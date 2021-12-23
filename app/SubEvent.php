<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubEvent extends Model
{
    use SoftDeletes;

    protected $table = 'sub_events';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'event_id',
        'event_type_id',
        'sub_event_category_id',
        'user_id',
        'description',
        'category',
        'location',
        'status',
        'timer',
        'scoreboard',
        'age',
        'latitude',
        'longitude',
        'start_date',
        'start_time',
        'end_date',
        'end_time',
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
