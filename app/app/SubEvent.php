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
        'title',
        'event_id',
        'created_at',
        'updated_at',
        'deleted_at',
        'description'
    ];
}
