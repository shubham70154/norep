<?php


namespace App\Http\Controllers\API;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;
use Illuminate\Support\Facades\Log;


class BaseController extends Controller
{
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
            $SERVER_API_KEY = 'AAAAVevGbvs:APA91bE1Ob11Y6-iQmWlAKSB7YOgDHElWYHXjcjzICNu5MObm_YFLhMUSIZ5zyh3O6GgtHFDbPrcSxgTnKqRKPI2VqmYfukoCMzcTcG1YXlaug1Wm2D3ULBZunipGxDAhA5LGLmsGBfJ';
            $url = 'https://fcm.googleapis.com/fcm/send';//env('FIREBASE_API_URL');
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
           // Log::info('dataString: ' . json_encode($dataString));
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
            $response = json_decode(curl_exec($ch));

            Log::info('response_check11 : ' . json_encode($response['success']));
            $notificationResponse = true;
            if ($response === FALSE) {
                Log::info('FCM_notification_curl_error: ' . curl_error($ch));
                die;
            }
            // } else if($response['succcess']) {
            //     Log::info('FCM_notification_send_successfully: ' . $response);
            //     $notificationResponse = true;
            // } else if($response['failure']) {
            //     Log::info('FCM_notification_send_failure: ' . $response);
            //     $notificationResponse = false;
            // } 
            curl_close($ch);
          
            return $notificationResponse;
        } catch (\Exception $e) {
            return $this->sendError('Oops something went wrong.', ['error'=> $e->getMessage()]);
        }
    }
}