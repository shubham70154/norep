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
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Helpers\Helper;
use App\Http\Requests\Request as RequestsRequest;
use App\SubEventSpecify;
use App\UserLeaderboard;
use DB, Validator, Illuminate\Support\Carbon;

use function GuzzleHttp\json_decode;

class LeaderBoardsApiController extends BaseController
{
    public function getEventLeaderBoard($event_id, $specified_id = null, $sub_event_id = null)
    {
        try {
            if (isset($event_id) && !is_null($event_id) && isset($specified_id) && !is_null($specified_id)
                && isset($sub_event_id) && !is_null($sub_event_id))
            {
                $eventDetail = Event::find($event_id);
                $sub_event_ids = SubEventSpecify::where([
                        ['event_id', $event_id],
                        ['event_specified_id', $specified_id],
                        ['sub_event_id', $sub_event_id]
                    ])->pluck('sub_event_id')->toArray();

                $getSubEvents = SubEvent::whereIn('id', $sub_event_ids)->get();
            
                $getAssignedParticipantLists = UserJoinedEvent::where([
                    ['event_id', $event_id],
                    ['event_specified_id', $specified_id]
                    ])->pluck('user_id')->toArray();
                
                $participantLists = User::select('id', 'name');
                $participantLists = $participantLists->addSelect(DB::raw( "'00' AS points"));
                $participantLists = $participantLists->addSelect(DB::raw( "'--' AS time"));
                $participantLists = $participantLists->whereIn('id', $getAssignedParticipantLists)->get();

                $participants = [];
                foreach($getSubEvents as $subevent){
                    $subevent->participants = $participantLists;
                    $subevent->scoreboard = json_decode($subevent->scoreboard);
                    $subevent->timer = json_decode($subevent->timer);
                    $participants[] = $subevent;
                }
                $eventDetail->sub_events = $participants;
                $eventDetail->total = ['participants' => $participantLists];
                return $this->sendResponse($eventDetail, 'LeaderBoard fetch successfully.');
            } else if (isset($event_id) && !is_null($event_id) && isset($specified_id) && !is_null($specified_id))
            {
                $eventDetail = Event::find($event_id);
                $sub_event_ids = SubEventSpecify::where([
                        ['event_id', $event_id],
                        ['event_specified_id', $specified_id]
                    ])->pluck('sub_event_id')->toArray();

                $getSubEvents = SubEvent::whereIn('id', $sub_event_ids)->get();
            
                $getAssignedParticipantLists = UserJoinedEvent::where([
                    ['event_id', $event_id],
                    ['event_specified_id', $specified_id]
                    ])->pluck('user_id')->toArray();
                
                $participantLists = User::select('id', 'name');
                $participantLists = $participantLists->addSelect(DB::raw( "'00' AS points"));
                $participantLists = $participantLists->addSelect(DB::raw( "'--' AS time"));
                $participantLists = $participantLists->whereIn('id', $getAssignedParticipantLists)->get();

                $participants = [];
                foreach($getSubEvents as $subevent){
                    $subevent->participants = $participantLists;
                    $subevent->scoreboard = json_decode($subevent->scoreboard);
                    $subevent->timer = json_decode($subevent->timer);
                    $participants[] = $subevent;
                }
                $eventDetail->sub_events = $participants;
                $eventDetail->total = ['participants' => $participantLists];
                return $this->sendResponse($eventDetail, 'LeaderBoard fetch successfully.');
            } else if (isset($event_id) && !is_null($event_id))
            {
                $eventDetail = Event::find($event_id);
                $getSubEvents = SubEvent::where([
                        ['event_id', $event_id],
                        ['status', 1]
                    ])->get();
            
                $getAssignedParticipantLists = UserJoinedEvent::where('event_id', $event_id)->pluck('user_id')->toArray();
                
                $participantLists = User::select('id', 'name');
                $participantLists = $participantLists->addSelect(DB::raw( "'00' AS points"));
                $participantLists = $participantLists->addSelect(DB::raw( "'--' AS time"));
                $participantLists = $participantLists->whereIn('id', $getAssignedParticipantLists)->get();

                $participants = [];
                foreach($getSubEvents as $subevent){
                    $SubEventSpecify = SubEventSpecify::where('sub_event_id', $subevent->id)->pluck('event_specified_id')->toArray();
                    $subevent->subeventspecify = $SubEventSpecify;
                    $SubEventSpecifyUser = UserJoinedEvent::select('event_specified_id')
                        ->where('event_id', $event_id)
                        ->whereIn('event_specified_id', $SubEventSpecify)->pluck('user_id')->toArray();
                    $participantLists = User::select('id', 'name');
                    $participantLists = $participantLists->addSelect(DB::raw( "'00' AS points"));
                    $participantLists = $participantLists->addSelect(DB::raw( "'--' AS time"));
                    $participantLists = $participantLists->whereIn('id', $SubEventSpecifyUser)->get();
                    $subevent->participants = $participantLists;
                    $subevent->scoreboard = json_decode($subevent->scoreboard);
                    $subevent->timer = json_decode($subevent->timer);
                    $participants[] = $subevent;
                }
                $eventDetail->sub_events = $participants;
                $eventDetail->total = ['participants' => $participantLists];
                return $this->sendResponse($eventDetail, 'LeaderBoard fetch successfully.');
            } else {
                return $this->sendError('Event not found.', ['error'=>'Event id not found!']);
            }

        } catch (\Exception $e) {
            return $this->sendError('Oops something went wrong.', ['error'=> $e->getMessage(),
            'line_no'=> $e->getLine()]);
        }
    }

    public function userEventLeaderboard(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required',
                'event_id' => 'required'
            ]);
        
            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors()->first());       
            }

            $getEventDetail = Event::select('id','name')->where('id',$request->event_id)->first();

            $userLeaderboards = UserLeaderboard::select('id','scoreboard','header','total_points','sub_event_id')->where([
                ['user_id', $request->user_id],
                ['event_id', $request->event_id],
                ['is_final_submit', 1]
            ])->get();

            if ($userLeaderboards) {
                $allSubevents = [];
                foreach($userLeaderboards as $leaderboard){
                    $getSubEventDetail = SubEvent::select('id','name')->where('id',$leaderboard->sub_event_id)->first();
                    $leaderboard->subevent_name = $getSubEventDetail->name;
                    $leaderboard->overall = '1st';
                    if($leaderboard->scoreboard) {
                        $scoreboardArray = [];
                        foreach(unserialize($leaderboard->scoreboard) as $key => $data) {
                            unset($data['task1']);
                            unset($data['task2']);
                            unset($data['task3']);
                            unset($data['task4']);
                            unset($data['task5']);
                            unset($data['task6']);
                            unset($data['task7']);
                            unset($data['task8']);
                            unset($data['task9']);
                            unset($data['task10']);
                            unset($data['reps']);
                            unset($data['weight']);
                            $data['rank'] = $key+1;
                            array_push($scoreboardArray, $data);
                        }
                        $leaderboard->scoreboard = $scoreboardArray;                        
                    }
                    $allSubevents[] = $leaderboard;
                }
                $result = ['event'=>$getEventDetail, 'subevent'=>$allSubevents];
                return $this->sendResponse($result, 'User Event Leaderboard get successfully.');    
            }
        } catch (\Exception $e) {
            return $this->sendError('Oops something went wrong.', ['error'=> $e->getMessage(),
            'line_no'=> $e->getLine()]);
        }
    }

}
