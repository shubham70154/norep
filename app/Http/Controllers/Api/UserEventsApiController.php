<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Event;
use App\SubEvent;
use App\User;
use App\File;
use App\UserEvent;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Helpers\Helper;
use DB, Validator, Illuminate\Support\Carbon;

class UserEventsApiController extends BaseController
{
    public function joinUserEvent(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'event_id' => 'required',
                'user_id' => 'required'
            ]);
        
            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors());       
            }
            
            DB::begintransaction();
            $result = UserEvent::create($request->all());
            DB::commit();
       
            return $this->sendResponse($result, 'Event joined successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Oops something went wrong.', ['error'=> $e->getMessage()]);
        }
    }

    public function getParticipantsListByEventId($eventId)
    {
        try {
            if (!is_null($eventId)) {
               $result = UserEvent::where([
                    ['event_id', $eventId]
                ])->pluck('user_id')->toArray();

                $participantList = User::whereIn('id', $result)->get();
                return $this->sendResponse($participantList, 'Participants list get successfully.');
            }
       
        } catch (\Exception $e) {
            return $this->sendError('Oops something went wrong.', ['error'=> $e->getMessage()]);
        }
    }

    public function getRefereeListByEventId($eventId)
    {
        try {
            if (!is_null($eventId)) {
               $eventDetails = Event::where([
                    ['id', $eventId]
                ])->select('referee_id')->get();

                $result = '';
                foreach ($eventDetails as $referees) {
                    if (!is_null($referees->referee_id)) {
                        $result .= str_replace('"',"",$referees->referee_id) .',';
                    }
                }
                $refereeArray = explode(',', rtrim($result, ','));
                $refereeIds = array_unique($refereeArray);

                $participantList = User::whereIn('id', $refereeIds)->get();
                return $this->sendResponse($participantList, 'Participants list get successfully.');
            }
       
        } catch (\Exception $e) {
            return $this->sendError('Oops something went wrong.', ['error'=> $e->getMessage()]);
        }
    }

}
