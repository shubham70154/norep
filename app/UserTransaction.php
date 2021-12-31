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
        'transaction_type',
        'deposite',
        'withdraw',
        'transaction_date_time',
        'joining_event_name',
        'amount_before_transaction',
        'amount_after_transaction',
        'created_at',
        'updated_at'
    ];
}
