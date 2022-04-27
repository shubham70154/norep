<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Mailable;
use App\Http\Controllers\Api\BaseController as BaseController;
use Exception;
use App\Mail\SendEmail;
use Log, DB;
use App\User;
use App\UserTransaction;
use App\AdminTransaction;

class TransferAmountToEventCreatorAccountJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    protected $event;

    protected $setting;

    protected $eventAmount;

    protected $eventCreatorFees;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 2;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($event, $eventAmount = null, $user_joined_event_details = null)
    {
        DB::begintransaction();
        // Update Admin transaction table (deposite start)
        $eventUserDetail = User::find(1); // get admin Account transaction details
        $eventtotalAmount = $eventUserDetail->total_amount + $eventAmount;
        
        $depositeData = [
            'transaction_type' => 'deposite',
            'user_joined_event_id' => $user_joined_event_details->id,
            'deposite_amount' => $eventAmount,
            'paypal_transaction_id' => $user_joined_event_details->paypal_transaction_id,
            'amount_before_transaction' => $eventUserDetail->total_amount,
            'amount_after_transaction' => $eventtotalAmount
        ];
        $eventUserDetail->total_amount = $eventtotalAmount;
        $eventUserDetail->save();
        $adminTransaction = AdminTransaction::create($depositeData);
        DB::commit();

        $this->event = $event; 
        $this->eventAmount = $eventAmount;
        $this->setting = DB::table('settings')->pluck('value', 'name')->toArray();
        $this->eventCreatorFees = ($this->eventAmount - $this->setting['EVENT_COMMISSION']);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $eventUserDetail = User::find($this->event->user_id);
            if ($eventUserDetail && isset($eventUserDetail->paypal_email_account) && 
                !is_null($eventUserDetail->paypal_email_account && !is_null($this->eventAmount)))
            {
                $ch = curl_init();
                if ($this->setting['IS_PAYPAL_LIVE'] == "false") {
                    curl_setopt($ch, CURLOPT_URL, $this->setting['PAYPAL_SANDBOX_URL'] . "v1/oauth2/token");
                } else {
                    curl_setopt($ch, CURLOPT_URL, $this->setting['PAYPAL_LIVE_URL'] . "v1/oauth2/token");
                }

                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
                curl_setopt($ch, CURLOPT_POST, 1);
                if ($this->setting['IS_PAYPAL_LIVE'] == "false") {
                    curl_setopt($ch, CURLOPT_USERPWD, $this->setting['PAYPAL_SANDBOX_CLIENT_ID'] . ":" . $this->setting['PAYPAL_SANDBOX_SECRATE_KEY']);
                } else {
                    curl_setopt($ch, CURLOPT_USERPWD, $this->setting['PAYPAL_LIVE_CLIENT_ID'] . ":" . $this->setting['PAYPAL_LIVE_SECRATE_KEY']);
                }
                
                $headers = array();
                $headers[] = "Accept: application/json";
                $headers[] = "Accept-Language: en_US";
                $headers[] = "Content-Type: application/x-www-form-urlencoded";
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                $results = curl_exec($ch);
                $getresult = json_decode($results);


                // PayPal Payout API for Send Payment from PayPal to PayPal account
                if ($this->setting['IS_PAYPAL_LIVE'] == "false") {
                    curl_setopt($ch, CURLOPT_URL, $this->setting['PAYPAL_SANDBOX_URL'] . "v1/payments/payouts");
                } else {
                    curl_setopt($ch, CURLOPT_URL, $this->setting['PAYPAL_LIVE_URL'] . "v1/payments/payouts");
                }
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

                $array = array('sender_batch_header' => array(
                        "sender_batch_id" => time(),
                        "email_subject" => "You have a Payment payout!",
                        "email_message" => "You have received a event joining fees payout."
                    ),
                    'items' => array(array(
                            "recipient_type" => "EMAIL",
                            "amount" => array(
                                "value" => $this->eventCreatorFees,
                                "currency" => $this->setting['PAYPAL_CURRENCY']
                            ),
                            "note" => "Thanks for the Payment!",
                            "sender_item_id" => time(),
                            "receiver" => $eventUserDetail->paypal_email_account
                        ))
                );
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($array));
                curl_setopt($ch, CURLOPT_POST, 1);

                $headers = array();
                $headers[] = "Content-Type: application/json";
                $headers[] = "Authorization: Bearer $getresult->access_token";
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                $payoutResult = curl_exec($ch);
                //print_r($result);
                $getPayoutResult = json_decode($payoutResult);
                if (curl_errno($ch)) {
                    echo 'Error:' . curl_error($ch);
                }
                curl_close($ch);
                Log::info("getPayoutResult ". json_encode($getPayoutResult));


                // check payout Acknowledgement
                $batchId = $getPayoutResult->batch_header->payout_batch_id;
                if ($batchId && !is_null($batchId))
                {
                    $this->checkPayoutAcknowledgement($batchId, $getresult);
                }
            }
        } catch(Exception $e) {
            Log::info("TransferAmountToEventCreatorAccountJob Error".print_r($e->getMessage(), true));
        }
    }

    public function checkPayoutAcknowledgement($batchId, $getresult)
    {
        $ch = curl_init();
        if ($this->setting['IS_PAYPAL_LIVE'] == "false") {
            curl_setopt($ch, CURLOPT_URL, $this->setting['PAYPAL_SANDBOX_URL'] . "v1/payments/payouts/". $batchId . "?fields=batch_header");
        } else {
            curl_setopt($ch, CURLOPT_URL, $this->setting['PAYPAL_LIVE_URL'] . "v1/payments/payouts/" . $batchId . "?fields=batch_header");
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $headers = array();
        $headers[] = "Content-Type: application/json";
        $headers[] = "Authorization: Bearer $getresult->access_token";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $payoutResult = curl_exec($ch);
        $getPayoutResult = json_decode($payoutResult);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        Log::info("paypout_".$batchId."_Acknowledgement : ". json_encode($getPayoutResult));

        $batch_status = $getPayoutResult->batch_header->batch_status;

        if ($batch_status = "PROCESSING" || $batch_status == "SUCCESS") {
            DB::begintransaction();
            // Update Admin transaction table (Withdraw start)
            $adminData = User::find(1); // get admin Account transaction details
            $adminUpdatedAmount = $adminData->total_amount - $this->eventCreatorFees;
            
            $depositeData = [
                'transaction_type' => 'withdraw',
                'user_id' => $this->event->user_id,
                'withdraw_amount' => $this->eventCreatorFees,
                'comssion' => $this->setting['EVENT_COMMISSION'],
                'paypal_transaction_id' => $batchId,
                'amount_before_transaction' => $adminData->total_amount,
                'amount_after_transaction' => $adminUpdatedAmount
            ];
            $adminData->total_amount = $adminUpdatedAmount;
            $adminData->save();
            $adminTransaction = AdminTransaction::create($depositeData);
            
            // Update user transaction table (deposite start)
            $eventUserDetail = User::find($this->event->user_id);
            $eventtotalAmount = ($eventUserDetail->total_amount + $this->eventCreatorFees);
            
            $eventId = $this->event->id;
            $depositeData = [
                'user_id' => $this->event->user_id,
                'joining_event_name' => "Event ID: $eventId :- " . $this->event->name,
                'amount_before_transaction' => $eventUserDetail->total_amount,
                'amount_after_transaction' => $eventtotalAmount,
                'deposite' => $this->eventCreatorFees,
                'transaction_type' => 'deposite'
            ];
            $eventUserDetail->total_amount = $eventtotalAmount;
            $eventUserDetail->save();
            $userTransaction = UserTransaction::create($depositeData);
            DB::commit();
            // Update user transaction table (deposite end)

            //Send Notification to event creator (start)
            $eventName = $this->event->name;
            $title = "Norep : Paypent received notification";
            $msg = "You have received payment amount ($this->eventCreatorFees ILS)for a new user has joined your event ($eventName)" ;
            $notificationResponse = BaseController::sendPayoutNotification($eventUserDetail->device_token, $title, $msg);
            BaseController::savePayoutNotification(null, $eventUserDetail->id, $title, $msg, $notificationResponse);
            //Send Notification to event creator (end)
        }
    }
}
