<?php

namespace App\Http\Controllers\Api;

use App\Event;
use App\SubEvent;
use App\User;
use App\File;
use App\UserJoinedEvent;
use App\UserTransaction;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\NotificationList;
use DB, Validator, Illuminate\Support\Carbon;

class NotificationsApiController extends BaseController
{
    public function getRefereeNotificationList($referee_id)
    {
        try {
            if (!is_null($referee_id)) {
                $result = NotificationList::select('id', 'title', 'message')->where([
                    ['referee_id', $referee_id],
                    ['response', 1]
                ])->get();
                return $this->sendResponse($result, 'Referee Notification list get successfully.');
            }
        } catch (\Exception $e) {
            return $this->sendError('Oops something went wrong.', ['error'=> $e->getMessage()]);
        }
    }

    public function getEventCreatorNotificationList($user_id)
    {
        try {
            if (!is_null($user_id)) {
                $result = NotificationList::select('id', 'title', 'message')->where([
                    ['user_id', $user_id],
                    ['response', 1]
                ])->get();
                return $this->sendResponse($result, 'Event Creator Notification list get successfully.');
            }
        } catch (\Exception $e) {
            return $this->sendError('Oops something went wrong.', ['error'=> $e->getMessage()]);
        }
    }

    public function deleteRefereeNotificationList(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'referee_id' => 'required'
            ]);
        
            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors()->first());       
            }
            NotificationList::where('referee_id', $request->referee_id)->delete();
            return $this->sendResponse((object)[], 'Notification deleted successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Oops something went wrong.', ['error'=> $e->getMessage()]);
        }
    }

    public function deleteEventCreatorNotificationList(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required'
            ]);
        
            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors()->first());       
            }
            NotificationList::where('user_id', $request->user_id)->delete();
            return $this->sendResponse((object)[], 'Notification deleted successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Oops something went wrong.', ['error'=> $e->getMessage()]);
        }
    }

}
