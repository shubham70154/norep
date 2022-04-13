<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyEventRequest;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Event;
use App\User;
use App\SubEvent;
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

    public function getUserDetails($id) {
        $user = User::find($id);
        if ($user) {
            return $user->name;
        } else {
            return '';
        }
    }
}
