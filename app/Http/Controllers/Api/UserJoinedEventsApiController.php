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

class UserJoinedEventsApiController extends BaseController
{
    public function joinUserEvent(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'event_id' => 'required',
                'user_id' => 'required',
                'event_specified_id' => 'required',
                'amount' => 'required',
                "paypal_status" => 'required',
                "paypal_transaction_id" => 'required',
                "paypal_response" => 'required',
                'device_type' => 'required'
            ]);
        
            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors()->first());       
            }
            
            $eventDetail = Event::find($request->event_id);
            //if user is joining virtual event (start)
            if ($eventDetail->event_type_id == 1) {
                DB::begintransaction();
                $request->paypal_response =  json_encode($request->paypal_response);
                $result = UserJoinedEvent::create($request->all());
                $eventUserDetail = User::find($eventDetail->user_id);

                DB::commit();
                //Send Notification to event creator (start)
                $joinedUserDetail = User::find($request->user_id);
                $title = "Norep : A new Athlete has joined the event";
                $msg = "A new Athlete (". ucfirst($joinedUserDetail->name).") has joined the event ". "$eventDetail->name" ."." ;
                $notificationResponse = $this->sendNotification($eventUserDetail->device_token, $title, $msg);
                $this->saveNotification(null, $eventUserDetail->id, $title, $msg, $notificationResponse);
                //Send Notification to event creator (end)

                $email_data['subject'] = "NoRep: Joined New Event";
                $email_data['email']  = $joinedUserDetail->email;
                $email_data['user_name']  = $joinedUserDetail->name;
                $email_data['event_details']  = $eventDetail;
                $email_data['page'] = "emails.user.join-event";
                
                $this->dispatch(new \App\Jobs\SendEmailJob($email_data));

                // transfer amount to event creator paypal account after the user/athlete join the event
                if ($request->paypal_status == 'COMPLETED' && !is_null($request->paypal_transaction_id)) {
                    $this->dispatch(new \App\Jobs\TransferAmountToEventCreatorAccountJob($eventDetail, $request->amount));
                }
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
                    $request->paypal_response =  json_encode($request->paypal_response);
                    $result = UserJoinedEvent::create($request->all());
                    $eventUserDetail = User::find($eventDetail->user_id);
                    DB::commit();
                    //Send Notification to event creator (start)
                    $joinedUserDetail = User::find($request->user_id);
                    $title = "Norep : A new Athlete has joined the event";
                    $msg = "A new Athlete (". ucfirst($joinedUserDetail->name).") has joined the event ". "$eventDetail->name" ."." ;
                    $notificationResponse = $this->sendNotification($eventUserDetail->device_token, $title, $msg);
                    $this->saveNotification(null, $eventUserDetail->id, $title, $msg, $notificationResponse);
                    //Send Notification to event creator (end)

                    //Send Notification to Judge/Referee (start)
                    $judgeDetail = User::find($freeRefereeLists[0]);
                    $title = "Norep: You have been invited to judge the new event";
                    $msg = "You have been invited to judge the new event ". "$eventDetail->name" ."." ;
                    $notificationResponse = $this->sendNotification($judgeDetail->device_token, $title, $msg);
                    $this->saveNotification($judgeDetail->id, null, $title, $msg, $notificationResponse);
                    //Send Notification to Judge/Referee (end)

                    //Send Notification to event creator, The invited judge has accepted the invitation to become a referee (start)
                    $judgeDetail = User::find($freeRefereeLists[0]);
                    $title = "Norep: The invited judge has accepted the invitation to become a referee";
                    $msg = "The invited judge (". ucfirst($judgeDetail->name).") has accepted the invitation to become a referee for event ". "$eventDetail->name" ."." ;
                    $notificationResponse = $this->sendNotification($eventUserDetail->device_token, $title, $msg);
                    $this->saveNotification(null, $eventUserDetail->id, $title, $msg, $notificationResponse);
                    //Send Notification to event creator, The invited judge has accepted the invitation to become a referee (end)

                    $email_data['subject'] = "NoRep: Joined New Event";
                    $email_data['email']  = $joinedUserDetail->email;
                    $email_data['user_name']  = $joinedUserDetail->name;
                    $email_data['event_details']  = $eventDetail;
                    $email_data['page'] = "emails.user.join-event";
                
                    $this->dispatch(new \App\Jobs\SendEmailJob($email_data));
                   // transfer amount to event creator paypal account after the user/athlete join the event
                    if ($request->paypal_status == 'COMPLETED' && !is_null($request->paypal_transaction_id)) {
                        $this->dispatch(new \App\Jobs\TransferAmountToEventCreatorAccountJob($eventDetail, $request->amount));
                    }
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
                                        ['type', '=', 'image'],
                                        ['status', 1]
                                    ])->get();
                    $videoFiles = DB::table('files')->select('id','url', 'type', 'event_id', 'sub_event_id')
                                        ->where([
                                            ['event_id', $event->id],
                                            ['sub_event_id', null],
                                            ['type', '=', 'video'],
                                            ['status', 1]
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
