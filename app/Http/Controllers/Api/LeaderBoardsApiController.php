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
            
                $getAssignedParticipantLists = UserEvent::where('event_id', $event_id)->pluck('user_id')->toArray();
                $participantLists = User::select("name");
                $participantLists = $participantLists->addSelect(DB::raw( "'example' AS newfield"));
                $participantLists = $participantLists->whereIn('id', $getAssignedParticipantLists)->get();

                $participants = [];
                foreach($getSubEvents as $subevent){
                    $subevent->participants = $participantLists;
                    $participants[] = $subevent;
                }
                $eventDetail->sub_events = $participants;
                return $eventDetail;
            } else {
                return $this->sendError('Event not found.', ['error'=>'Event id not found!']);
            }

        } catch (\Exception $e) {
            return $this->sendError('Oops something went wrong.', ['error'=> $e->getMessage()]);
        }
    }

}
