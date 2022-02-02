<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\UserJoinedEvent;
use App\Event;
use App\User;
use DB,Log;

class ManualNotificationsController extends Controller
{
    public function index()
    {
        $users = User::where('user_type' ,'!=', 'Admin')->whereNotNull('device_token')->get();
        return view('admin.manual_notifications.index', compact('users'));
    }

    public function sendNotifications(Request $request)
    {
        $users = User::whereIn('id', request('ids'))->get();
        foreach($users as $user)
        {
            $this->notificationSend($user->device_token, request('title'), request('message'));
        }
        return response(null, 204);
    }

    public function notificationSend($device_tokens, $title = '', $body = '')
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
            if ($response === FALSE) {
                Log::info('FCM_notification_curl_error: ' . curl_error($ch));
                die;
            } else {
                Log::info('FCM_notification_send_successfully: ' . $response);
            } 
            curl_close($ch);
          
            return $response;
        } catch (\Exception $e) {
            return $this->sendError('Oops something went wrong.', ['error'=> $e->getMessage()]);
        }
    }
}
