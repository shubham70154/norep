<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Event;
use App\SubEvent;
use App\User;
use App\File;
use App\UserJoinedEvent;
use App\UserTransaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Helpers\Helper;
use App\Http\Requests\Request as RequestsRequest;
use DB, Validator, Illuminate\Support\Carbon;

class UserWalletsApiController extends BaseController
{
    public function getUserWallet($user_id)
    {
        try {
            if (isset($user_id) && !is_null($user_id))
            {
                $userEvents = Event::where([
                    ['user_id', $user_id],
                    ['status', 1]
                ])->orderBy('start_date', 'DESC')->pluck('id')->toArray();

                $eventsAmount = UserJoinedEvent::whereIn('event_id', $userEvents)
                ->select('event_id', DB::raw('sum(amount) as total'), DB::raw('count(user_id) as total_participant'))
                ->groupBy('event_id')
                ->get();

                $totalAmount = 0;
                $events = [];
                foreach($eventsAmount as $event)
                {
                    $eventDetail = Event::findOrFail($event->event_id);
                    $imagefiles = DB::table('files')->where([
                        ['event_id', $eventDetail->id],
                        ['type', '=', 'image']
                    ])->select('url')->get();
    
                    $videofiles = DB::table('files')->where([
                        ['event_id', $eventDetail->id],
                        ['type', '=', 'video']
                    ])->select('url')->get();
                    $eventDetail->images =  $imagefiles;
                    $eventDetail->vidoes =  $videofiles;
                    $eventDetail->event_total_amount =  $event->total;
                    $eventDetail->event_total_participant =  $event->total_participant;
                    $events[] = $eventDetail;
                    $totalAmount = $totalAmount + $event->total;
                }
                $result = ['event_amount' => $events, 'total_amount' => $totalAmount];
                return $this->sendResponse($result, 'User wallet fetch successfully.');
            } else {
                return $this->sendError('User not found.', ['error'=>'User id not found!']);
            }
            
        } catch (\Exception $e) {
            return $this->sendError('Oops something went wrong.', ['error'=> $e->getMessage()]);
        }
    }

    public function eventJoinedParticipantLists($event_id)
    {
        try {
            if (isset($event_id) && !is_null($event_id))
            {
                $events = UserJoinedEvent::where('event_id', $event_id)->get();

                $participants = [];
                foreach($events as $event){
                    $userDetail = User::findOrFail($event->user_id);
                    $userDetail->event_joined_amount = $event->amount;
                    $participants[] = $userDetail;
                }
                $eventDetail = Event::findOrFail($event->event_id);
                $result = ['event' => $eventDetail, 'participants' => $participants];
                return $this->sendResponse($result, 'Event Participant List fetch successfully.');
            } else {
                return $this->sendError('Event not found.', ['error'=>'Event id not found!']);
            }
        } catch (\Exception $e) {
            return $this->sendError('Oops something went wrong.', ['error'=> $e->getMessage()]);
        }
    }

    public function userWalletDepositeAmount(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required',
                'deposite' => 'required'
            ]);
        
            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors());       
            }
            DB::begintransaction();
            $userTransaction = UserTransaction::create($request->all());
            //Update user wallet
            $userDetails = User::find($request->user_id);
            $userDetails->total_amount = $userDetails->total_amount + $request->deposite;
            $userDetails->save();
            DB::commit();

        } catch (\Exception $e) {
            return $this->sendError('Oops something went wrong.', ['error'=> $e->getMessage()]);
        }
    }
}
