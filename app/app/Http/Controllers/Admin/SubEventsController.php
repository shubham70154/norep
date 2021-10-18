<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Event;
use App\SubEvent;
use Log;
use DB;

class SubEventsController extends Controller
{
    public function subEventList($id)
    {
        $subEvents = SubEvent::all();
        
        return view('admin.sub_events.index', compact('subEvents'));
    }

    public function showSubEvent($id)
    {
        $subEvent = SubEvent::findOrFail($id);
        return view('admin.sub_events.show', compact('subEvent'));
    }
}
