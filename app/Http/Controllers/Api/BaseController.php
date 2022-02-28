<?php


namespace App\Http\Controllers\API;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;
use Illuminate\Support\Facades\Log;
use App\NotificationList;
use DB;


class BaseController extends Controller
{

    protected $setting;

    public function __construct()
    {
        $this->setting = DB::table('settings')->pluck('value', 'name')->toArray();
    }

    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($result, $message)
    {
    	$response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];


        return response()->json($response, 200);
    }


    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($error, $errorMessages = [], $code = 404)
    {
    	$response = [
            'success' => false,
            'message' => $errorMessages,
        ];


        // if(!empty($errorMessages)){
        //     $response['data'] = $errorMessages;
        // }


        return response()->json($response, $code);
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function sendNotification($device_tokens, $title = '', $body = '')
    {
        try {
            $SERVER_API_KEY = $this->settings['FCM_SERVER_API_KEY'];
            $url = $this->settings['FCM_API_URL'];//env('FIREBASE_API_URL');
            // payload data, it will vary according to requirement
            $data = [
                "to" => $device_tokens, // for multiple device ids
                "notification" => [
                    "title" => $title,
                    "body" => $body,
                    'vibrate' => 1,
                    'sound' => 1 
                ]
            ];
            $dataString = json_encode($data);
        
            $headers = [
                'Authorization: key=' . $SERVER_API_KEY,
                'Content-Type: application/json',
            ];
            Log::info('dataString: ' . json_encode($dataString));
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
            $response = curl_exec($ch);

            $checkResponse = json_decode($response);
            $notificationResponse = true;
            if ($response === FALSE) {
                Log::info('FCM_notification_curl_error: ' . curl_error($ch));
                die;
            } else if(isset($checkResponse->success) && $checkResponse->success == 1) {
                Log::info('FCM_notification_send_successfully: ' . $response);
                $notificationResponse = true;
            } else if(isset($checkResponse->failure) && $checkResponse->failure == 1) {
                Log::info('FCM_notification_send_failure: ' . $response);
                $notificationResponse = false;
            } 
            curl_close($ch);
          
            return $notificationResponse;
        } catch (\Exception $e) {
            return $this->sendError('Oops something went wrong.', ['error'=> $e->getMessage()]);
        }
    }

    public function saveNotification($referee_id = null, $user_id = null, $title = '', $msg = '', $response = '')
    {
        try {
            $saveNotificationData =[
                'referee_id' => $referee_id,
                'user_id' => $user_id,
                'title' => $title,
                'message' => $msg,
                'response' => $response,
            ];
            NotificationList::create($saveNotificationData);
        } catch (\Exception $e) {
            return $this->sendError('Oops something went wrong.', ['error'=> $e->getMessage()]);
        }
    }

    public static function sendPayoutNotification($device_tokens, $title = '', $body = '')
    {
        try {
            $setting = DB::table('settings')->pluck('value', 'name')->toArray();
            $SERVER_API_KEY = $setting['FCM_SERVER_API_KEY'];
            $url = $setting['FCM_API_URL'];//env('FIREBASE_API_URL');
            // payload data, it will vary according to requirement
            $data = [
                "to" => $device_tokens, // for multiple device ids
                "notification" => [
                    "title" => $title,
                    "body" => $body,
                    'vibrate' => 1,
                    'sound' => 1 
                ]
            ];
            $dataString = json_encode($data);
        
            $headers = [
                'Authorization: key=' . $SERVER_API_KEY,
                'Content-Type: application/json',
            ];
            Log::info('dataString: ' . json_encode($dataString));
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
            $response = curl_exec($ch);

            $checkResponse = json_decode($response);
            $notificationResponse = true;
            if ($response === FALSE) {
                Log::info('FCM_payout_notification_curl_error: ' . curl_error($ch));
                die;
            } else if(isset($checkResponse->success) && $checkResponse->success == 1) {
                Log::info('FCM_payout_notification_send_successfully: ' . $response);
                $notificationResponse = true;
            } else if(isset($checkResponse->failure) && $checkResponse->failure == 1) {
                Log::info('FCM_payout_notification_send_failure: ' . $response);
                $notificationResponse = false;
            } 
            curl_close($ch);
          
            return $notificationResponse;
        } catch (\Exception $e) {
            return [ 'error_msg' =>'Oops payout_something went wrong.', 'error'=> $e->getMessage()];
        }
    }

    public static function savePayoutNotification($referee_id = null, $user_id = null, $title = '', $msg = '', $response = '')
    {
        try {
            $saveNotificationData =[
                'referee_id' => $referee_id,
                'user_id' => $user_id,
                'title' => $title,
                'message' => $msg,
                'response' => $response,
            ];
            NotificationList::create($saveNotificationData);
        } catch (\Exception $e) {
            return [ 'error_msg' =>'Oops payout_something went wrong.', 'error'=> $e->getMessage()];
        }
    }

    public function getSettings() {
        $settings = DB::table('settings')->pluck('value', 'name')->toArray();
        if (count($settings) > 0) {
            return $this->sendResponse($settings, 'All settings get successfully.');
        }
    }
}