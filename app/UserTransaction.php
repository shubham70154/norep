<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserTransaction extends Model
{
    protected $table = 'user_transactions';

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    protected $fillable = [
        'user_id',
        'deposite',
        'withdraw',
        'transaction_date_time',
        'created_at',
        'updated_at'
    ];
}
