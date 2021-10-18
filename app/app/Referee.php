<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Referee extends Model
{
    use SoftDeletes;
    protected $table = 'referees';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'alternate_number',
        'generate_password',
        'gender',
        'age',
        'details',
        'address',
        'pincode',
        'city',
        'state',
        'country',
        'status',
        'profile_image',
        'updated_at',
        'deleted_at'
    ];
}
