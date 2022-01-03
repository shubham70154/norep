<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRefereeRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateRefereeRequest;
use App\Http\Requests\UpdateUserRequest;
use App\UserJoinedEvent;
use App\Event;
use App\User;
use App\{Country,State,City};

class RefereesController extends Controller
{
    public function index()
    {
        abort_unless(\Gate::allows('user_access'), 403);

        $referees = UserJoinedEvent::all();

        $referleeLists = [];
        foreach($referees as $referee) {
            $event = Event::find($referee->event_id);
            $refereeDetails = User::find($referee->referee_id);

            $referee->event = $event;
            $referee->referee = $refereeDetails;
            $referleeLists[] = $referee;
        }
        
        return view('admin.referees.index', compact('referleeLists'));
    }

    public function create()
    {
        abort_unless(\Gate::allows('user_create'), 403);

        $countries = Country::get(["name","id"]);

        return view('admin.referees.create', compact('countries'));
    }

    public function store(StoreRefereeRequest $request)
    {
        abort_unless(\Gate::allows('user_create'), 403);

        $referee = Referee::create($request->all());
        
        return redirect()->route('admin.referees.index');
    }

    public function edit(Referee $referee)
    {
        abort_unless(\Gate::allows('user_edit'), 403);
        $countries = Country::get(["name","id"]);
        return view('admin.referees.edit', compact('referee', 'countries'));
    }

    public function update(UpdateRefereeRequest $request, Referee $referee)
    {
        abort_unless(\Gate::allows('user_edit'), 403);

        $referee->update($request->all());

        return redirect()->route('admin.referees.index');
    }

    public function show($id)
    {
        $details = UserJoinedEvent::find($id);

        $event = Event::find($details->event_id);
        $refereeDetails = User::find($details->referee_id);

        $details->event = $event;
        $details->referee = $refereeDetails;
        return view('admin.referees.show', compact('details'));
    }

    public function destroy(Referee $referee)
    {
        abort_unless(\Gate::allows('user_delete'), 403);

        $referee->delete();

        return back();
    }

    public function getState(Request $request)
    {
        $data['states'] = State::where("country_id",$request->country_id)
                    ->get(["name","id"]);
        return response()->json($data);
    }
    public function getCity(Request $request)
    {
        $data['cities'] = City::where("state_id",$request->state_id)
                    ->get(["name","id"]);
        return response()->json($data);
    }
}
