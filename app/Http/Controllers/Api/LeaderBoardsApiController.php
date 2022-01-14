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
use App\UserLeaderboard;
use DB, Validator, Illuminate\Support\Carbon;

class LeaderBoardsApiController extends BaseController
{
    public function getEventLeaderBoard($event_id)
    {
        try {
            if (isset($event_id) && !is_null($event_id))
            {
                $eventDetail = Event::findOrFail($event_id);
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
                    $subevent->participants = $participantLists;
                    $participants[] = $subevent;
                }
                $eventDetail->sub_events = $participants;
                $eventDetail->total = ['participants' => $participantLists];
                return $this->sendResponse($eventDetail, 'LeaderBoard fetch successfully.');
            } else {
                return $this->sendError('Event not found.', ['error'=>'Event id not found!']);
            }

        } catch (\Exception $e) {
            return $this->sendError('Oops something went wrong.', ['error'=> $e->getMessage()]);
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
                return $this->sendError('Validation Error.', $validator->errors());       
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
                    $getSubEventDetail = SubEvent::select('id','name')->where('id',$leaderboard->sub_event_id)->get();
                    $leaderboard->subevents = $getSubEventDetail;
                    $leaderboard->overall = '1st';
                    if($leaderboard->scoreboard) {
                        $scoreboardArray = [];
                        foreach(unserialize($leaderboard->scoreboard) as $key => $leaderboard) {
                            unset($leaderboard['task1']);
                            unset($leaderboard['task2']);
                            unset($leaderboard['task3']);
                            unset($leaderboard['task4']);
                            unset($leaderboard['task5']);
                            unset($leaderboard['reps']);
                            
                            $leaderboard['rank'] = $key+1;
                            array_push($scoreboardArray, $leaderboard);
                        }
                        $leaderboard['test'] = $scoreboardArray;                        
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
