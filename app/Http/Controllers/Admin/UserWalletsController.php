<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\UserTransaction;
use App\Event;
use App\User;
use DB,Log;

class UserWalletsController extends Controller
{
    public function index($id)
    {
        $userTransaction = UserTransaction::where([
            ['user_id', $id]
        ])->orderBy('transaction_date_time')->get();
        
        $transactions = [];
        $transactionResult = [];
        foreach($userTransaction as $transaction){
            if(isset($transaction->id) && !is_null($transaction->id)) {
                $transactions['id'] = $transaction->id;
            }
            if(isset($transaction->transaction_type) && !is_null($transaction->transaction_type)) {
                $transactions['transaction_type'] = $transaction->transaction_type;
            }
            if(isset($transaction->deposite) && !is_null($transaction->deposite)) {
                $transactions['deposite'] = $transaction->deposite;
            }
            if(isset($transaction->withdraw) && !is_null($transaction->withdraw)) {
                $transactions['withdraw'] = $transaction->withdraw;
            }
            if(isset($transaction->joining_event_name) && !is_null($transaction->joining_event_name)) {
                $transactions['joining_event_name'] = $transaction->joining_event_name;
            }
            if(isset($transaction->amount_before_transaction) && !is_null($transaction->amount_before_transaction)) {
                $transactions['amount_before_transaction'] = $transaction->amount_before_transaction;
            }
            if(isset($transaction->amount_after_transaction) && !is_null($transaction->amount_after_transaction)) {
                $transactions['amount_after_transaction'] = $transaction->amount_after_transaction;
            }
            if(isset($transaction->transaction_date_time) && !is_null($transaction->transaction_date_time)) {
                $transactions['transaction_date_time'] = $transaction->transaction_date_time;
            }
            $transactionResult[] = $transactions;
            $transactions = [];
        }
        
        return view('admin.walletmanagement.index', compact('transactionResult'));
    }

}
