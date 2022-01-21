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
use App\Helpers\Helper;
use App\Http\Requests\Request as RequestsRequest;
use DB, Validator, Illuminate\Support\Carbon;

class UserJoinedEventsApiController extends BaseController
{
    public function joinUserEvent(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'event_id' => 'required',
                'user_id' => 'required',
                'event_specified_id' => 'required'
            ]);
        
            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors());       
            }
            
            $eventDetail = Event::findOrFail($request->event_id);
            if (!is_null($eventDetail->referee_id))
            {
                // Get all referee array list for this event(start)
                $result = str_replace('"',"",$eventDetail->referee_id);
                $refereeArray = explode(',', rtrim($result, ','));
                $refereeIds = array_unique($refereeArray);
                // Get all referee array list for this event(end)

                $getAssignedRefereeLists = UserJoinedEvent::where('event_id', $request->event_id)->pluck('referee_id')->toArray();
                
                $diff1 = array_diff($refereeIds, $getAssignedRefereeLists);
                $diff2 = array_diff($getAssignedRefereeLists, $refereeIds);
                $freeRefereeLists = array_merge($diff1, $diff2);
                
                if (count($freeRefereeLists) > 0) {
                    DB::begintransaction();
                    $request->request->add(['referee_id' => $freeRefereeLists[0]]);
                    $result = UserJoinedEvent::create($request->all());

                    // Update user transaction table (deposite start)
                    $eventUserDetail = User::findOrFail($eventDetail->user_id);
                    $eventtotalAmount = $eventUserDetail->total_amount + $request->amount;
                    
                    $depositeData = [
                        'user_id' => $eventDetail->user_id,
                        'joining_event_name' => $eventDetail->name,
                        'amount_before_transaction' => $eventUserDetail->total_amount,
                        'amount_after_transaction' => $eventtotalAmount,
                        'deposite' => $request->amount,
                        'transaction_type' => 'deposite'
                    ];
                    $eventUserDetail->total_amount = $eventtotalAmount;
                    $eventUserDetail->save();
                    $userTransaction = UserTransaction::create($depositeData);
                    // Update user transaction table (deposite end)

                    DB::commit();
                    return $this->sendResponse($result, 'Event joined successfully.');
                } else {
                    return $this->sendResponse((object)[], "All referee's assigned, can't join event.");
                }
            } else {
                return $this->sendResponse((object)[], "No referees are assigned to this event");
            }
        } catch (\Exception $e) {
            return $this->sendError('Oops something went wrong.', ['error'=> $e->getMessage()]);
        }
    }

    public function getParticipantsListByEventId($eventId)
    {
        try {
            if (!is_null($eventId)) {
                $result = UserJoinedEvent::where([
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
            
            $result = UserJoinedEvent::where([
                ['event_id', $request->event_id],
                ['user_id', $request->user_id]
            ])->first();
            if ($result) {
                return $this->sendResponse($result, 'Already joined.');     
            } else {
                return $this->sendResponse((object)[], 'Event not joined.'); 
            }
       
        } catch (\Exception $e) {
            return $this->sendError('Oops something went wrong.', ['error'=> $e->getMessage()]);
        }
    }

}
