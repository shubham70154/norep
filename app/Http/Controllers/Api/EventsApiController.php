<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Event;
use App\SubEvent;
use App\User;
use App\File;
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
                'start_date' => 'required|after_or_equal:now',
                'start_time' => 'required',
                'user_id' => 'required',
                'player_limit' => 'required|min:0'
            ]);
        
            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors());       
            }
            
            DB::begintransaction();
            $event = Event::create($request->all());

            $images = [];
            $videos = [];
            foreach($request->images as $image) {
                $file = File::create([
                    'url' => $image,
                    'type' => 'image',
                    'event_id' => $event->id
                ]);
                $images[] = $image;
            }
            foreach($request->videos as $video) {
                $file = File::create([
                    'url' => $video,
                    'type' => 'video',
                    'event_id' => $event->id
                ]);
                $videos[] = $video;
            }

            DB::commit();
       
            $event->images = $images;
            $event->videos = $videos;
            return $this->sendResponse($event, 'Event created successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Oops something went wrong.', ['error'=> $e->getMessage()]);
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
                $event->users;
                $event->event_types;
                
                $imageFiles = DB::table('files')->select('id','url', 'type', 'event_id', 'sub_event_id')
                                ->where('type','=', 'image')->where('event_id', $event->id)->get();
                $videoFiles = DB::table('files')->select('id','url', 'type', 'event_id', 'sub_event_id')
                                ->where('type', '=','video')->where('event_id', $event->id)->get();
                $event->images = $imageFiles;
                $event->videos = $videoFiles;
                
                return $this->sendResponse($event, 'Event details get successfully.');    
            } else {
                return $this->sendError('Event not found.', ['error'=>'Event id not found!']);
            }
        } catch (\Exception $e) {
            return $this->sendError('Oops something went wrong.', ['error'=> $e->getMessage()]);
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
            if ($eventLists) {
                return $this->sendResponse($eventLists, 'Event list get successfully.');
            } else {
                return $this->sendError('Oops something went wrong.', ['error'=> 'List not found']);
            }
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
            if ($eventLists) {
                return $this->sendResponse($eventLists, 'past event list get successfully.');
            } else {
                return $this->sendError('Oops something went wrong.', ['error'=> 'List not found']);
            }
            
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
            if ($eventLists) {
                return $this->sendResponse($eventLists, 'future event list get successfully.');
            } else {
                return $this->sendError('Oops something went wrong.', ['error'=> 'List not found']);
            }
        } catch (\Exception $e) {
            return $this->sendError('Oops something went wrong.', ['error'=>$e->getMessage()]);
        }
    }

    public function getRunningEventList()
    {
        try {
            $eventLists = Event::where([
                        ['status' , 1],
                        ['start_date', '<=', Carbon::today()],
                        ['end_date', '>=', Carbon::today()]
                        ])->orderBy('start_date', 'DESC')->get();
        
            if ($eventLists) {
                return $this->sendResponse($eventLists, 'Running event list get successfully.');
            } else {
                return $this->sendError('Oops something went wrong.', ['error'=> 'List not found']);
            }
        } catch (\Exception $e) {
            return $this->sendError('Oops something went wrong.', ['error'=>$e->getMessage()]);
        }
    }

    public function createSubEvent(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'event_id' =>'required|exists:events,id',
                'name' => 'required',
                'description' => 'required',
                'category' => 'required',
                'event_type_id' => 'required',
                'location' => 'required',
                'user_id' => 'required',
            ]);
        
            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors());       
            }
            
            DB::begintransaction();
            $subEvent = SubEvent::create($request->all());
            $images = [];
            $videos = [];
            foreach($request->images as $image) {
                $file = File::create([
                    'url' => $image,
                    'type' => 'image',
                    'event_id' => $request->event_id,
                    'sub_event_id' => $subEvent->id
                ]);
                $images[] = $image;
            }
            foreach($request->videos as $video) {
                $file = File::create([
                    'url' => $video,
                    'type' => 'video',
                    'event_id' => $subEvent->id
                ]);
                $videos[] = $video;
            }
            DB::commit();
            $subEvent->images = $images;
            $subEvent->videos = $videos;

            return $this->sendResponse($subEvent, 'Sub event created successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Oops something went wrong.', ['error'=> $e->getMessage()]);
        }
    }

    public function getSubEventList($event_id)
    {
        try {
            $subeventLists = SubEvent::where([
                            ['status' , 1],
                            ['event_id', $event_id]
                        ])->orderBy('created_at', 'DESC')->get();

            if ($subeventLists) {
                return $this->sendResponse($subeventLists, 'Sub event list get successfully.');
            } else {
                return $this->sendError('Oops something went wrong.', ['error'=> 'List not found']);
            }
        } catch (\Exception $e) {
            return $this->sendError('Oops something went wrong.', ['error'=> $e->getMessage()]);
        }
    }

    public function showSubEventDetails($subEventId)
    {
        try {
            if (isset($subEventId) && !is_null($subEventId)) {
                $subevent = SubEvent::findOrFail($subEventId);

                $imageFiles = DB::table('files')->select('id','url', 'type', 'event_id', 'sub_event_id')
                                ->where('type','=', 'image')->where('sub_event_id', $subevent->id)->get();
                $videoFiles = DB::table('files')->select('id','url', 'type', 'event_id', 'sub_event_id')
                                ->where('type', '=','video')->where('sub_event_id', $subevent->id)->get();
                $subevent->images = $imageFiles;
                $subevent->videos = $videoFiles;
                
                return $this->sendResponse($subevent, 'Sub event details get successfully.');    
            } else {
                return $this->sendError('Sub event not found.', ['error'=>'Sub event id not found!']);
            }
        } catch (\Exception $e) {
            return $this->sendError('Oops something went wrong.', ['error'=>'Oops something went wrong!']);
        }
    }


    public function refereeAllocatedEvents($referee_id)
    {
        try {
            if (isset($referee_id) && !is_null($referee_id)) {
                
                $events = Event::where('referee_id', 'like', '%' . $referee_id . '%')->get();

                $allevents = [];
                foreach($events as $event) {
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
                
                if($allevents) {
                    return $this->sendResponse($allevents, 'Allocated event list get successfully.');    
                } else {
                    return $this->sendError('Sub event not found.', ['error'=>'Allocated event list not found!']);     
                }
                
            } else {
                return $this->sendError('Sub event not found.', ['error'=>'Allocated event list not found!']);
            }
        } catch (\Exception $e) {
            return $this->sendError('Oops something went wrong.', ['error'=> $e->getMessage()]);
        }
    }

    public function assignEventReferees(Request $request)
    {
        try {
            $event = Event::where('id', $request->event_id)->update(['referee_id'=>$request->referee_id]);
            if ($event) {
                return $this->sendResponse($event, 'Referee assigned successfully.');
            } else {
                return $this->sendError('Oops something went wrong.', ['error'=> 'Event not found']);
            }
        } catch (\Exception $e) {
            return $this->sendError('Oops something went wrong.', ['error'=> $e->getMessage()]);
        }
    }

    public function updateEventPlayerLimit(Request $request)
    {
        try {
            $event = Event::where('id', $request->event_id)->update(['player_limit' => $request->player_limit]);
            if ($event) {
                return $this->sendResponse($event, 'Event player limit updated successfully.');
            } else {
                return $this->sendError('Oops something went wrong.', ['error'=> 'Event not found']);
            }
        } catch (\Exception $e) {
            return $this->sendError('Oops something went wrong.', ['error'=> $e->getMessage()]);
        }
    }
}
