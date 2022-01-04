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

    // public function create()
    // {
    //     abort_unless(\Gate::allows('user_create'), 403);

    //     $countries = Country::get(["name","id"]);

    //     return view('admin.referees.create', compact('countries'));
    // }

    // public function store(StoreRefereeRequest $request)
    // {
    //     abort_unless(\Gate::allows('user_create'), 403);

    //     $referee = Referee::create($request->all());
        
    //     return redirect()->route('admin.referees.index');
    // }

    // public function edit(Referee $referee)
    // {
    //     abort_unless(\Gate::allows('user_edit'), 403);
    //     $countries = Country::get(["name","id"]);
    //     return view('admin.referees.edit', compact('referee', 'countries'));
    // }

    // public function update(UpdateRefereeRequest $request, Referee $referee)
    // {
    //     abort_unless(\Gate::allows('user_edit'), 403);

    //     $referee->update($request->all());

    //     return redirect()->route('admin.referees.index');
    // }

    // public function show($id)
    // {
    //     $details = UserJoinedEvent::find($id);

    //     $event = Event::find($details->event_id);
    //     $refereeDetails = User::find($details->referee_id);

    //     $details->event = $event;
    //     $details->referee = $refereeDetails;
    //     return view('admin.referees.show', compact('details'));
    // }

    // public function destroy(Referee $referee)
    // {
    //     abort_unless(\Gate::allows('user_delete'), 403);

    //     $referee->delete();

    //     return back();
    // }
}
