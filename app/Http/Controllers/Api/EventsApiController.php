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
use DB, Validator, Illuminate\Support\Carbon;

class EventsApiController extends BaseController
{

    public function create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'description' => 'required',
                'price' => 'required|min:0',
                'start_date' => 'required',
                'start_time' => 'required',
                'user_id' => 'required',
                'player_limit' => 'required|min:0'
            ]);
        
            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors());       
            }
            
            DB::begintransaction();
            $event = Event::create($request->all());
            DB::commit();

            return $this->sendResponse($event, 'Event created successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Oops something went wrong.', ['error'=>'Oops something went wrong!']);
        }
    }

    public function update(UpdateEventRequest $request, Event $product)
    {
        return $product->update($request->all());
    }

    public function showEventDetails($id)
    {
        try {
            if (isset($id) && !is_null($id)) {
                $event = Event::findOrFail($id);
                return $this->sendResponse($event->user(), 'Event details get successfully.');    
            } else {
                return $this->sendError('Event not found.', ['error'=>'Event id not found!']);
            }
        } catch (\Exception $e) {
            return $this->sendError('Oops something went wrong.', ['error'=>'Oops something went wrong!']);
        }
    }

    public function destroy(Event $product)
    {
        return $product->delete();
    }

    public function sendUserNotification()
    {
        $DeviceToekn = User::whereNotNull('device_token')->pluck('device_token')->all();
        return $this->sendResponse($DeviceToekn, 'Token send successfully.', 'Token send successfully.');
    }

    public function getAllEventList()
    {
        try {
            $eventLists = Event::where('status', 1)->orderBy('start_date', 'DESC')->get();

            return $this->sendResponse($eventLists, 'Event list get successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Oops something went wrong.', ['error'=>'Oops something went wrong!']);
        }
    }

    public function getPastEventList()
    {
        try {
            $eventLists = Event::where([
                        ['status' , 1],
                        ['start_date', '<=', Carbon::today()]
                        ])->orderBy('start_date', 'DESC')->get();

            return $this->sendResponse($eventLists, 'past event list get successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Oops something went wrong.', ['error'=>$e->getMessage()]);
        }
    }

    public function getFutureEventList()
    {
        try {
            $eventLists = Event::where([
                        ['status' , 1],
                        ['start_date', '>=', Carbon::today()]
                        ])->orderBy('start_date', 'DESC')->get();

            return $this->sendResponse($eventLists, 'future event list get successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Oops something went wrong.', ['error'=>$e->getMessage()]);
        }
    }
}
