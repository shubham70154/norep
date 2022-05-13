<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyEventRequest;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Event;
use App\EventSpecify;
use App\User;
use App\SubEvent;
use App\SubEventSpecify;
use App\UserJoinedEvent;
use Log;
use DB;
use Carbon\Carbon;

class EventsController extends Controller
{
    public function index()
    {
        abort_unless(\Gate::allows('product_access'), 403);

        $events = Event::all();

        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        abort_unless(\Gate::allows('product_create'), 403);

        return view('admin.events.create');
    }

    public function store(StoreEventRequest $request)
    {
        abort_unless(\Gate::allows('product_create'), 403);

        $event = Event::create($request->all());

        return redirect()->route('admin.events.index');
    }

    public function edit(Event $event)
    {
        abort_unless(\Gate::allows('product_edit'), 403);

        return view('admin.events.edit', compact('event'));
    }

    public function update(UpdateEventRequest $request, Event $event)
    {
        abort_unless(\Gate::allows('product_edit'), 403);

        $event->update($request->all());

        return redirect()->route('admin.events.index');
    }

    public function show(Event $event)
    {
        $result = '';
        $result = str_replace('"',"",$event->referee_id) .',';

        $refereeArray = explode(',', rtrim($result, ','));
        $refereeIds = array_unique($refereeArray);

        $participantList = User::whereIn('id', $refereeIds)->get();

        $refereenames = '';
        foreach($participantList as $user) {
            $refereenames .= $user->name . ',';
        }
        $refereenames = rtrim($refereenames, ',');
        $event->refereenames = $refereenames;
        
        return view('admin.events.show', compact('event'));
    }

    public function destroy(Event $event)
    {
        abort_unless(\Gate::allows('product_delete'), 403);

        $event->delete();

        return back();
    }

    public function massDestroy(MassDestroyEventRequest $request)
    {
        Event::whereIn('id', request('ids'))->delete();

        return response(null, 204);
    }

    public function getRunningEventList()
    {
        $eventLists = Event::where([
                    ['status' , 4],
                    ['start_date', '<=', Carbon::today()],
                    ['end_date', '>=', Carbon::today()]
                    ])->orderBy('start_date', 'DESC')->get();
            
        $allevents = [];
        foreach($eventLists as $event) {
            $imagefiles = DB::table('files')->where([
                ['event_id', $event->id],
                ['sub_event_id', null],
                ['type', '=', 'image']
            ])->select('url')->get();

            $videofiles = DB::table('files')->where([
                ['event_id', $event->id],
                ['sub_event_id', null],
                ['type', '=', 'video']
            ])->select('url')->get();
            $event->images =  $imagefiles;
            $event->vidoes =  $videofiles;
            $allevents[] = $event;
        }

        return view('admin.events.runningevents', compact('allevents'));
    }

    public function getFutureEventList()
    {
        $eventLists = Event::where([
                    ['status' , 4],
                    ['start_date', '>', Carbon::today()]
                    ])->orderBy('start_date', 'DESC')->get();
        
        $allevents = [];
        foreach($eventLists as $event) {
            $imagefiles = DB::table('files')->where([
                ['event_id', $event->id],
                ['type', '=', 'image']
            ])->select('url')->get();

            $videofiles = DB::table('files')->where([
                ['event_id', $event->id],
                ['type', '=', 'video']
            ])->select('url')->get();
            $event->images =  $imagefiles;
            $event->vidoes =  $videofiles;
            $allevents[] = $event;
        }
        return view('admin.events.upcomingevents', compact('allevents'));
    }

    public function getPastEventList()
    {
        $eventLists = Event::where([
                    ['status' , 4],
                    ['start_date', '<', Carbon::today()]
                    ])->orderBy('start_date', 'DESC')->get();
        
        $allevents = [];
        foreach($eventLists as $event) {
            $imagefiles = DB::table('files')->where([
                ['event_id', $event->id],
                ['type', '=', 'image']
            ])->select('url')->get();

            $videofiles = DB::table('files')->where([
                ['event_id', $event->id],
                ['type', '=', 'video']
            ])->select('url')->get();
            $event->images =  $imagefiles;
            $event->vidoes =  $videofiles;
            $allevents[] = $event;
        }
        return view('admin.events.pastevents', compact('allevents'));
    }

    public static function getUserDetails($id) {
        $user = User::find($id);
        if ($user) {
            return ucfirst($user->name);
        } else {
            return '';
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
                return view('admin.sub_events.participants', compact('participantList'));
               // return $this->sendResponse($participantList, 'Participants list get successfully.');
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
                return view('admin.sub_events.refereelist', compact('refereeList'));
                //return $this->sendResponse($refereeList, 'Referee list get successfully.');
            }
        } catch (\Exception $e) {
            return $this->sendError('Oops something went wrong.', ['error'=> $e->getMessage()]);
        }
    }

    public static function eventOrganizerAmountEarned($id) {
        // $event_ids = Event::where('user_id', $id)->pluck('id')->toArray();
        // if ($event_ids) {
        //     $getJoinedEventsAmount = UserJoinedEvent::whereIn('event_id', $event_ids)->sum('amount');
        //     if ($getJoinedEventsAmount) {
        //         return $getJoinedEventsAmount;
        //     } else {
        //         return 0;
        //     }
        // } else {
        //     return 0;
        // }
        $user = User::find($id);
        if ($user) {
            return ucfirst($user->amount);
        } else {
            return '';
        }
    }

    public static function getEventCategoryList($eventid, $sub_eventid = null) {
        if (!is_null($eventid) && !is_null($sub_eventid)) {
            $SubEventSpecify = SubEventSpecify::where('event_id', $eventid)
                            ->where('sub_event_id', $sub_eventid)->select('event_specified_id')->first();
            $EventSpecify = EventSpecify::find($SubEventSpecify->event_specified_id);
            if ($EventSpecify) {
                return $EventSpecify->title;
            } else {
                return '';
            }
        } elseif(!is_null($eventid) && is_null($sub_eventid)) {
            $EventSpecify = EventSpecify::where('event_id', $eventid)->pluck('title')->toArray();
            if ($EventSpecify) {
                return implode(', ' , $EventSpecify);
            } else {
                return '';
            }
        }
    }

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
                    $SubEventSpecify = SubEventSpecify::where('sub_event_id', $subevent->id)->pluck('event_specified_id')->toArray();
                    $subevent->subeventspecify = $SubEventSpecify;
                    $SubEventSpecifyUser = UserJoinedEvent::where('event_id', $event_id)
                        ->whereIn('event_specified_id', $SubEventSpecify)->pluck('user_id')->toArray();
                    $participantLists = User::select('id', 'name');
                    $participantLists = $participantLists->addSelect(DB::raw( "'00' AS points"));
                    $participantLists = $participantLists->addSelect(DB::raw( "'--' AS time"));
                    $participantLists = $participantLists->whereIn('id', $SubEventSpecifyUser)->get();
                    $subevent->participants = $participantLists;
                    //$subevent->participants = $participantLists;
                    $subevent->scoreboard = json_decode($subevent->scoreboard);
                    $subevent->timer = json_decode($subevent->timer);
                    $participants[] = $subevent;
                }
                $eventDetail->sub_events = $participants;
                $eventDetail->total = ['participants' => $participantLists];

                $specifiedList = DB::table('event_specified_for')->select('id','title')
                        ->where('event_id' , $event_id)->get();
                return view('admin.events.leaderboard', compact('eventDetail', 'specifiedList'));
               // return $this->sendResponse($eventDetail, 'LeaderBoard fetch successfully.');
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
                   return $SubEventSpecify = SubEventSpecify::where('sub_event_id', $subevent->id)->pluck('event_specified_id')->toArray();
                    $subevent->subeventspecify = $SubEventSpecify;
                    $SubEventSpecifyUser = UserJoinedEvent::where('event_id', $event_id)
                        ->whereIn('event_specified_id', $SubEventSpecify)->pluck('user_id')->toArray();
                    $participantLists = User::select('id', 'name');
                    $participantLists = $participantLists->addSelect(DB::raw( "'00' AS points"));
                    $participantLists = $participantLists->addSelect(DB::raw( "'--' AS time"));
                    $participantLists = $participantLists->whereIn('id', $SubEventSpecifyUser)->get();
                    $subevent->participants = $participantLists;
                    //$subevent->participants = $participantLists;
                    $subevent->scoreboard = json_decode($subevent->scoreboard);
                    $subevent->timer = json_decode($subevent->timer);
                    $participants[] = $subevent;
                }
                $eventDetail->sub_events = $participants;
                $eventDetail->total = ['participants' => $participantLists];
                $specifiedList = DB::table('event_specified_for')->select('id','title')
                        ->where('event_id' , $event_id)->get();
                return view('admin.events.leaderboard', compact('eventDetail', 'specifiedList'));
                //return $this->sendResponse($eventDetail, 'LeaderBoard fetch successfully.');
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
                    $SubEventSpecifyUser = UserJoinedEvent::where('event_id', $event_id)
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
                $specifiedList = DB::table('event_specified_for')->select('id','title')
                        ->where('event_id' , $event_id)->get();
                return view('admin.events.leaderboard', compact('eventDetail', 'specifiedList'));
            } else {
                $eventDetail = [];
                return view('admin.events.leaderboard', compact($eventDetail));
            }

        } catch (\Exception $e) {
            return $this->sendError('Oops something went wrong.', ['error'=> $e->getMessage(),
            'line_no'=> $e->getLine()]);
        }
    }
}
