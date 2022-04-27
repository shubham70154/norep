<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdminTransaction extends Model
{
    protected $table = 'admin_transactions';

    protected $dates = [
        'created_at'
    ];

    protected $fillable = [
        'transaction_type',
        'user_joined_event_id',
        'deposite_amount',
        'withdraw_amount',
        'user_id',
        'comssion',
        'paypal_transaction_id',
        'amount_before_transaction',
        'amount_after_transaction',
        'created_at'
    ];
}
