<?php

namespace App\Http\Controllers\Admin;

use App\AdminTransaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\UserTransaction;
use App\Event;
use App\User;
use App\UserJoinedEvent;
use DB,Log;

class UserWalletsController extends Controller
{
    public function index($id)
    {
        $adminTransaction = AdminTransaction::orderBy('created_at', 'DESC')->get();
        
        $transactions = [];
        $transactionResult = [];
        foreach($adminTransaction as $transaction){
            if(isset($transaction->id) && !is_null($transaction->id)) {
                $transactions['id'] = $transaction->id;
            }
            if(isset($transaction->transaction_type) && !is_null($transaction->transaction_type)) {
                $transactions['transaction_type'] = $transaction->transaction_type;
            }
            if(isset($transaction->deposite_amount) && !is_null($transaction->deposite_amount)) {
                $transactions['deposite_amount'] = $transaction->deposite_amount;
            }
            if(isset($transaction->withdraw_amount) && !is_null($transaction->withdraw_amount)) {
                $transactions['withdraw_amount'] = $transaction->withdraw_amount;
            }
            if(isset($transaction->user_id) && !is_null($transaction->user_id)) {
                $transactions['user_id'] = $transaction->user_id;
            }

            if(isset($transaction->user_joined_event_id) && !is_null($transaction->user_joined_event_id)) {
                $transactions['user_joined_event_id'] = $transaction->user_joined_event_id;
            }
            if(isset($transaction->comssion) && !is_null($transaction->comssion)) {
                $transactions['comssion'] = $transaction->comssion;
            }
            if(isset($transaction->paypal_transaction_id) && !is_null($transaction->paypal_transaction_id)) {
                $transactions['paypal_transaction_id'] = $transaction->paypal_transaction_id;
            }
            if(isset($transaction->amount_before_transaction) && !is_null($transaction->amount_before_transaction)) {
                $transactions['amount_before_transaction'] = $transaction->amount_before_transaction;
            }
            if(isset($transaction->amount_after_transaction) && !is_null($transaction->amount_after_transaction)) {
                $transactions['amount_after_transaction'] = $transaction->amount_after_transaction;
            }
            if(isset($transaction->created_at) && !is_null($transaction->created_at)) {
                $transactions['created_at'] = $transaction->created_at;
            }
            $transactionResult[] = $transactions;
            $transactions = [];
        }
        return view('admin.walletmanagement.index', compact('transactionResult'));
    }

    public static function getEventDetails($id) {
        $userJoinedEvent = UserJoinedEvent::find($id);
        if ($userJoinedEvent) {
            $event = Event::find($userJoinedEvent->event_id);
            if ($event) {
                return "Event Id: $event->id :- " . ucfirst($event->name);
            } else {
                return '';
            }
        } else {
            return '';
        }
    }

}
