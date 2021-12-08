<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class File extends Model
{
    use SoftDeletes;

    protected $table = 'files';

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    protected $fillable = [
        'url',
        'type',
        'event_id',
        'sub_event_id',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
