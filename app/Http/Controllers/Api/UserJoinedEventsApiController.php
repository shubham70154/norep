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
                return $this->sendError('Validation Error.', $validator->errors()->first());       
            }
            
            $eventDetail = Event::findOrFail($request->event_id);
            //if user is joining virtual event (start)
            if ($eventDetail->event_type_id == 1) {
                DB::begintransaction();
                $result = UserJoinedEvent::create($request->all());

                // Update user transaction table (deposite start)
                $eventUserDetail = User::findOrFail($eventDetail->user_id);
                $eventtotalAmount = $eventUserDetail->total_amount + $request->amount;
                
                // $depositeData = [
                //     'user_id' => $eventDetail->user_id,
                //     'joining_event_name' => $eventDetail->name,
                //     'amount_before_transaction' => $eventUserDetail->total_amount,
                //     'amount_after_transaction' => $eventtotalAmount,
                //     'deposite' => $request->amount,
                //     'transaction_type' => 'deposite'
                // ];
                // $eventUserDetail->total_amount = $eventtotalAmount;
                // $eventUserDetail->save();
                // $userTransaction = UserTransaction::create($depositeData);
                // Update user transaction table (deposite end)

                DB::commit();
                //Send Notification to to event creator
                $joinedUserDetail = User::findOrFail($request->user_id);
                $title = "A new Athlete has joined the event";
                $msg = "A new Athlete (". ucfirst($joinedUserDetail->name).") has joined the event ". "$eventDetail->name" ."." ;
                $this->sendNotification($eventUserDetail->device_token, $title, $msg);

                return $this->sendResponse($result, 'Event joined successfully.');
            }
            //if user is joining virtual event (end)

            //if user is joining onsite event (start)
            if (!is_null($eventDetail->referee_id) && $eventDetail->event_type_id == 2)
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
            //if user is joining onsite event (end)
        } catch (\Exception $e) {
            return $this->sendError('Oops something went wrong.', ['error'=> $e->getMessage(),
            'line_no'=> $e->getLine()]);
        }
    }

    public function getParticipantsListByEventId($eventId)
    {
        try {
            if (!is_null($eventId)) {
                $result = UserJoinedEvent::where([
                    ['event_id', $eventId]
                ])->get();

                $participantList = [];
                foreach($result as $res) {
                   $participant = User::where('id', $res->user_id)->first();
                   $referee = User::select('id', 'name')->where('id', $res->referee_id)->first();
                   $participant->assigned_referee = $referee;
                   $participantList[] = $participant;
                }

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
            //    $eventDetails = Event::where([
            //         ['id', $eventId]
            //     ])->select('referee_id', 'user_id')->get();

            //     $result = '';
            //     foreach ($eventDetails as $referees) {
            //         if (!is_null($referees->referee_id)) {
            //             $result .= str_replace('"',"",$referees->referee_id) .',';
            //         }
            //     }
            //     $refereeArray = explode(',', rtrim($result, ','));
            //     $refereeIds = array_unique($refereeArray);

            //     $participantList = User::whereIn('id', $refereeIds)->get();
                $result = UserJoinedEvent::where([
                    ['event_id', $eventId]
                ])->get();

                $refereeList = [];
                foreach($result as $res) {
                    if(!is_null($res->referee_id) ) {
                        $referee = User::where('id', $res->referee_id)->first();
                    
                        $participant = User::select('id', 'name')->where('id', $res->user_id)->first();
                        $referee->assigned_participant = $participant;
                        $refereeList[] = $referee;
                    }
                    
                }
                return $this->sendResponse($refereeList, 'Referee list get successfully.');
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
                return $this->sendError('Validation Error.', $validator->errors()->first());       
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

    public function getJoinedEventsListByUserId($userId)
    {
        try {
            if (!is_null($userId)) {
                $result = UserJoinedEvent::where([
                    ['user_id', $userId]
                ])->get();

                $eventList = [];
                foreach($result as $res) {
                   $event = Event::where('id', $res->event_id)->first();
                   
                   $imageFiles = DB::table('files')->select('id','url', 'type', 'event_id', 'sub_event_id')
                                    ->where([
                                        ['event_id', $event->id],
                                        ['sub_event_id', null],
                                        ['type', '=', 'image']
                                    ])->get();
                    $videoFiles = DB::table('files')->select('id','url', 'type', 'event_id', 'sub_event_id')
                                        ->where([
                                            ['event_id', $event->id],
                                            ['sub_event_id', null],
                                            ['type', '=', 'video']
                                        ])->get();
                    $event->images = $imageFiles;
                    $event->videos = $videoFiles;
                    $eventList[] = $event;
                }

                return $this->sendResponse($eventList, 'User joined event list get successfully.');
            }
       
        } catch (\Exception $e) {
            return $this->sendError('Oops something went wrong.', ['error'=> $e->getMessage()]);
        }
    }

}
