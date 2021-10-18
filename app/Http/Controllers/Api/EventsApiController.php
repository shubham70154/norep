<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Event;
use App\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Helpers\Helper;
use DB, Validator;

class EventsApiController extends BaseController
{
    public function index()
    {
        $events = Event::all();

        return $this->sendResponse($events, 'Events retrieved successfully.');
    }

    public function create(Request $request)
    {
       // try {
        $rules = [
            'name' => 'required',
            'description' => 'required',
            'price' => 'required|min:0',
            'start_date' => 'required',
            'start_time' => 'required',
            'player_limit' => 'required|min:0'
        ];
        $validated = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'price' => 'required|min:0',
            'start_date' => 'required',
            'start_time' => 'required',
            'player_limit' => 'required|min:0'
        ]);
        //Helper::custom_validator($request->all(), $rules);

          //  DB::begintransaction();
            // $this->validate($request, [
            //     'name' => 'required',
            //     'description' => 'required',
            //     'price' => 'required|min:0',
            //     'start_date' => 'required',
            //     'start_time' => 'required',
            //     'player_limit' => 'required|min:0'
            // ]);

           
            $event = Event::create($request->all());
          //  DB::commit();
            return $this->sendResponse($event, 'event created successfully.');
        // } catch (\Exception $e) {
        //    // return $this->sendError('Oops something went wrong.', ['error'=>'Oops something went wrong!']);
        // }
        
    }

    public function update(UpdateEventRequest $request, Event $product)
    {
        return $product->update($request->all());
    }

    public function show($id)
    {
       // return $id;
        $event = Event::findOrFail($id);

        return $this->sendResponse($event, 'Events retrieved successfully.');
    }

    public function destroy(Event $product)
    {
        return $product->delete();
    }

    public function sendUserNotification()
    {
        $DeviceToekn = User::whereNotNull('device_token')->pluck('device_token')->all();
        return $this->sendResponse($DeviceToekn, 'Events retrieved successfully.', 'Events retrieved successfully.');
    }
}
