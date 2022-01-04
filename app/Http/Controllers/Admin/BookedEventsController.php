<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\UserJoinedEvent;
use App\Event;
use App\User;
use DB,Log;

class BookedEventsController extends Controller
{
    public function index()
    {
        $bookedEvents = UserJoinedEvent::select('event_id', DB::raw('sum(amount) as total'), DB::raw('count(user_id) as total_participant'))
                            ->groupBy('event_id')->get();
        $events = [];
        foreach($bookedEvents as $event)
        {
            $eventDetail = Event::find($event->event_id);
            if($eventDetail) {
                $eventDetail->event_total_amount =  $event->total;
                $eventDetail->event_total_participant =  $event->total_participant;
                $events[] = $eventDetail;
            }
        }
        
        return view('admin.bookedevents.index', compact('events'));
    }

}
