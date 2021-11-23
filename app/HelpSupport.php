<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HelpSupport extends Model
{

    protected $table = 'help_supports';

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'title',
        'description',
        'user_id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
