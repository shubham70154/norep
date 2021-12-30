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
use DB, Validator;
use Carbon\Carbon;

class EventsApiController extends BaseController
{

    public function create(Request $request)
    {
        try {
            //return $mytime = Carbon::now();
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'description' => 'required',
                'price' => 'required|min:0',
                'start_date' => 'required|after_or_equal:today',
                'start_time' => 'required',
                'end_date' => 'after_or_equal:start_date',
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

            return $this->sendResponse($allevents, 'Event list get successfully.');
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
            return $this->sendResponse($allevents, 'past event list get successfully.');
            
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
            return $this->sendResponse($allevents, 'future event list get successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Oops something went wrong.', ['error'=>$e->getMessage()]);
        }
    }

    public function getRunningEventList(Request $request)
    {
        try {
            $user = $request->user();
            $eventLists = Event::where([
                        ['status' , 1],
                       // ['user_id' , '!=', $user->id],
                        ['start_date', '<=', Carbon::today()],
                        ['end_date', '>=', Carbon::today()]
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
        
            return $this->sendResponse($allevents, 'Running event list get successfully.');
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
                'start_date' => 'required|after_or_equal:today',
                'end_date' => 'after_or_equal:start_date',
                'category' => 'required',
                'event_type_id' => 'required',
                'location' => 'required',
                'user_id' => 'required',
            ]);
        
            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors());       
            }

            // $files = $request->file('docs');

            // if($request->hasFile('docs'))
            // {
            //     foreach ($files as $file) {
            //         $file->store('images');
            //     }
            //     return $request->all();
            // }
          //  return "hi";
            $checkSubEvents = DB::table('sub_events')->where([
                ['start_date', $request->start_date],
                ['start_time', $request->start_time],
                ['event_id', $request->event_id],
            ])->first();
            // if($checkSubEvents){
            //     return $this->sendError('Validation Error.', 'Sub Event already created with same start date and time');
            // }

            $eventdata = DB::table('events')->where([
                ['id', $request->event_id],
                ['status', 1]
            ])->first();
            $eventStartDate = date('Y-m-d', strtotime($eventdata->start_date));
            $eventEndDate = date('Y-m-d', strtotime($eventdata->end_date));
            $requestStartDate = date('Y-m-d', strtotime($request->start_date));
            $requestEndDate = date('Y-m-d', strtotime($request->end_date));
            
            if(($eventdata->start_date > $request->start_date ||
                $eventdata->end_date < $request->start_date)
                ) {
                return $this->sendError('Validation Error.', 'Sub Event should be between event start date and time');
            }

            if(($eventdata->end_date > $request->end_date )) {
                return $this->sendError('Validation Error.', 'Sub Event should be between eventssss start date and time');
            }
            
            
            return $request->all();
            DB::begintransaction();
            $data = [
                'event_id' => $request->event_id,
                'age' => $request->age,
                'category' => $request->category,
                'name' => $request->name,
                'event_type_id' => $request->event_type_id,
                'user_id' => $request->user_id,
                'description' => $request->description,
                'location' => $request->location,
                'timer' => json_encode($request->timer),
                'scoreboard' => json_encode($request->scoreboard),
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'start_date' => $request->start_date,
                'start_time' => $request->start_time,
                'end_date' => $request->end_date,
                'end_time' => $request->end_time,
                'sub_event_category_id' => $request->sub_event_category_id
            ];
            $subEvent = SubEvent::create($data);

            $req_images = $request->images;
            $req_videos = $request->videos;
            $req_docs = $request->docs;
            
            $images = [];
            $videos = [];
            $docs = [];
            foreach($req_images as $image) {
                $file = File::create([
                    'url' => $image,
                    'type' => 'image',
                    'event_id' => $request->event_id,
                    'sub_event_id' => $subEvent->id
                ]);
                $images[] = $image;
            }

            foreach($req_videos as $video) {
                $file = File::create([
                    'url' => $video,
                    'type' => 'video',
                    'event_id' => $request->event_id,
                    'sub_event_id' => $subEvent->id
                ]);
                $videos[] = $video;
            }

            foreach($req_docs as $doc) {
                $file = File::create([
                    'url' => $doc,
                    'type' => 'doc',
                    'event_id' => $request->event_id,
                    'sub_event_id' => $subEvent->id
                ]);
                $docs[] = $doc;
            }
            DB::commit();
            $subEvent->images = $images;
            $subEvent->videos = $videos;
            $subEvent->docs = $docs;
            $subEvent->scoreboard = json_decode($subEvent->scoreboard);
            $subEvent->timer = json_decode($subEvent->timer);

            return $this->sendResponse($subEvent, 'Sub event created successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Oops something went wrong.', ['error'=> $e->getMessage(),
            'line'=> $e->getLine()]);
        }
    }

    public function getSubEventList($event_id)
    {
        try {
            $subeventLists = SubEvent::where([
                            ['status' , 1],
                            ['event_id', $event_id]
                        ])->orderBy('created_at', 'DESC')->get();

            $allevents = [];
            foreach($subeventLists as $event) {
                $imagefiles = DB::table('files')->where([
                    ['event_id', $event_id],
                    ['sub_event_id', $event->id],
                    ['type', '=', 'image']
                ])->select('url')->get();

                $videofiles = DB::table('files')->where([
                    ['event_id', $event_id],
                    ['sub_event_id', $event->id],
                    ['type', '=', 'video']
                ])->select('url')->get();
                $event->images =  $imagefiles;
                $event->vidoes =  $videofiles;
                $allevents[] = $event;
            }

            return $this->sendResponse($allevents, 'Sub event list get successfully.');
            
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
            return $this->sendResponse($event, 'Referee assigned successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Oops something went wrong.', ['error'=> $e->getMessage()]);
        }
    }

    public function updateEventPlayerLimit(Request $request)
    {
        try {
            $event = Event::where('id', $request->event_id)->update(['player_limit' => $request->player_limit]);
            return $this->sendResponse($event, 'Event player limit updated successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Oops something went wrong.', ['error'=> $e->getMessage()]);
        }
    }

    public function getEventTimelines($event_id)
    {
        if (isset($event_id) && !is_null($event_id))
        {
            $runningEventLists = SubEvent::where([
                ['status' , 1],
                ['event_id' , $event_id],
                ['start_date', '<=', Carbon::today()],
                ['end_date', '>=', Carbon::today()]
                ])->orderBy('start_date', 'DESC')->get();
            
            $runningEvents = [];
            foreach($runningEventLists as $event) {
                $imagefiles = DB::table('files')->where([
                    ['event_id', $event_id],
                    ['sub_event_id', $event->id],
                    ['type', '=', 'image']
                ])->select('url')->get();

                $videofiles = DB::table('files')->where([
                    ['event_id', $event_id],
                    ['sub_event_id', $event->id],
                    ['type', '=', 'video']
                ])->select('url')->get();
                $event->images =  $imagefiles;
                $event->vidoes =  $videofiles;
                $event->timeline_status =  'running';
                $event->scoreboard = json_decode($event->scoreboard);
                $event->timer = json_decode($event->timer);
                $runningEvents[] = $event;
            }

            $upcomingEventLists = SubEvent::where([
                ['status' , 1],
                ['event_id' , $event_id],
                ['start_date', '>=', Carbon::today()]
                ])->orderBy('start_date', 'DESC')->get();

            $upcomingEvents = [];
            foreach($upcomingEventLists as $event) {
                $imagefiles = DB::table('files')->where([
                    ['event_id', $event_id],
                    ['sub_event_id', $event->id],
                    ['type', '=', 'image']
                ])->select('url')->get();

                $videofiles = DB::table('files')->where([
                    ['event_id', $event_id],
                    ['sub_event_id', $event->id],
                    ['type', '=', 'video']
                ])->select('url')->get();
                $event->images =  $imagefiles;
                $event->vidoes =  $videofiles;
                $event->timeline_status =  'upcoming';
                $event->scoreboard = json_decode($event->scoreboard);
                $event->timer = json_decode($event->timer);
                $upcomingEvents[] = $event;
            }
            
            $pastEventLists = SubEvent::where([
                ['status' , 1],
                ['event_id' , $event_id],
                ['start_date', '<=', Carbon::today()]
                ])->orderBy('start_date', 'DESC')->get();

            $pastEvents = [];
            foreach($pastEventLists as $event) {
                $imagefiles = DB::table('files')->where([
                    ['event_id', $event_id],
                    ['sub_event_id', $event->id],
                    ['type', '=', 'image']
                ])->select('url')->get();

                $videofiles = DB::table('files')->where([
                    ['event_id', $event_id],
                    ['sub_event_id', $event->id],
                    ['type', '=', 'video']
                ])->select('url')->get();
                $event->images =  $imagefiles;
                $event->vidoes =  $videofiles;
                $event->timeline_status =  'past';
                $event->scoreboard = json_decode($event->scoreboard);
                $event->timer = json_decode($event->timer);
                $pastEvents[] = $event;
            }
            $timeSubEvents = array_merge($runningEvents, $upcomingEvents, $pastEvents);
            return $this->sendResponse($timeSubEvents, 'Event timeline list get successfully.');
        }
    }

    public function getSubEventCategoryLists()
    {
        try {
            $categoryList = DB::table('sub_event_categories')->select('id','name')
                            ->where('status' , 1)->get();


            return $this->sendResponse($categoryList, 'Sub event category list get successfully.');
            
        } catch (\Exception $e) {
            return $this->sendError('Oops something went wrong.', ['error'=> $e->getMessage()]);
        }
    }
}
