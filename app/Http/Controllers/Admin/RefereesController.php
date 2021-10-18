<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRefereeRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateRefereeRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Referee;
use App\{Country,State,City};

class RefereesController extends Controller
{
    public function index()
    {
        abort_unless(\Gate::allows('user_access'), 403);

        $referees = Referee::all();

        return view('admin.referees.index', compact('referees'));
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

    public function show(Referee $referee)
    {
        abort_unless(\Gate::allows('user_show'), 403);

        return view('admin.referees.show', compact('referee'));
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
