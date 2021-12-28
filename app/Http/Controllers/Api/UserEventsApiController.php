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
use App\Http\Requests\Request as RequestsRequest;
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
            $eventDetail = Event::findOrFail($request->event_id);
            // Get all referee array list for this event(start)
            $result = str_replace('"',"",$eventDetail->referee_id);
            $refereeArray = explode(',', rtrim($result, ','));
            $refereeIds = array_unique($refereeArray);
            // Get all referee array list for this event(end)

            $getAssignedRefereeLists = UserEvent::where('event_id', $request->event_id)->pluck('referee_id');
            
            $diff1 = array_diff($refereeIds, $getAssignedRefereeLists);
            $diff2 = array_diff($getAssignedRefereeLists, $refereeIds);
            return array_merge($diff1, $diff2);
            // $freeReferee = [];
            // foreach ($refereeIds as $referee) {
            //     if (!in_array($referee, $getAssignedRefereeLists)) {
            //         $freeReferee[] = $referee;
            //     }
            // }
            // return $freeReferee;

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

    public function checkUserJoinedEvents(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'event_id' => 'required',
                'user_id' => 'required'
            ]);
        
            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors());       
            }
            
            $result = UserEvent::where([
                ['event_id', $request->event_id],
                ['user_id', $request->user_id]
            ])->first();
            if ($result) {
                return $this->sendResponse($result, 'Already joined.');     
            } else {
                return $this->sendError('Not Joined.', ['error'=>'Event not joined']);
            }
       
        } catch (\Exception $e) {
            return $this->sendError('Oops something went wrong.', ['error'=> $e->getMessage()]);
        }
    }

}
