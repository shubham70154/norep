<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Event;
use App\EventPayment;
use App\SubEvent;
use App\User;
use App\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Helpers\Helper;
use App\UserJoinedEvent;
use App\UserLeaderboard;
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
                'price' => 'required|min:4.5',
                'start_date' => 'required|after_or_equal:today',
                'start_time' => 'required',
                'end_date' => 'after_or_equal:start_date',
                'user_id' => 'required',
                'player_limit' => 'required|min:1'
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

    public function deleteEvent(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'event_id' => 'required'
            ]);
        
            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors());       
            }
            $event = Event::where('id', $request->event_id)->delete();
            $subEvent = SubEvent::where('event_id', $request->event_id)->delete();
            $userJoinedEvent = UserJoinedEvent::where('event_id', $request->event_id)->delete();
            $userLeaderboard = UserLeaderboard::where('event_id', $request->event_id)->delete();
            $file = File::where('event_id', $request->event_id)->delete();
            $eventPayment = EventPayment::where('event_id', $request->event_id)->delete();
            return $this->sendResponse((object)[], 'Event deleted successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Oops something went wrong.', ['error'=> $e->getMessage()]);
        }
    }

    public function deleteSubEvent(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'sub_event_id' => 'required'
            ]);
        
            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors());       
            }
            $subEvent = SubEvent::where('id', $request->sub_event_id)->delete();
            $userLeaderboard = UserLeaderboard::where('sub_event_id', $request->sub_event_id)->delete();
            $file = File::where('sub_event_id', $request->sub_event_id)->delete();
            return $this->sendResponse((object)[], 'Event deleted successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Oops something went wrong.', ['error'=> $e->getMessage()]);
        }
    }

    public function eventUpdate(Request $request, $event_id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'description' => 'required',
                'price' => 'min:4.5',
                'start_date' => 'after_or_equal:today',
                'end_date' => 'after_or_equal:start_date',
                'player_limit' => 'min:1'
            ]);
        
            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors());       
            }
          
            DB::begintransaction();
            $event = Event::where('id', $event_id)->update($request->all());
            $images = [];
            $videos = [];
            if ($request->images) {
                $file = File::where([
                    ['event_id', $event_id],
                    ['type', 'image']
                ])->delete();
                foreach($request->images as $image) {
                    $file = File::create([
                        'url' => $image,
                        'type' => 'image',
                        'event_id' => $event->id
                    ]);
                    $images[] = $image;
                }
            }

            if ($request->videos) {
                $file = File::where([
                    ['event_id', $event_id],
                    ['type', 'video']
                ])->delete();
                foreach($request->videos as $video) {
                    $file = File::create([
                        'url' => $video,
                        'type' => 'video',
                        'event_id' => $event->id
                    ]);
                    $videos[] = $video;
                }
            }    
            
            DB::commit();
       
            $event->images = $images;
            $event->videos = $videos;
            return $this->sendResponse($event, 'Event Updated successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Oops something went wrong.', ['error'=> $e->getMessage()]);
        }
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
            if($checkSubEvents){
                return $this->sendError('Validation Error.', 'Sub Event already created with same start date and time');
            }

            $eventdata = DB::table('events')->where([
                ['id', $request->event_id],
                ['status', 1]
            ])->first();
            
            if(($eventdata->start_date > $request->start_date ||
                $eventdata->end_date < $request->start_date)
                ) {
                return $this->sendError('Validation Error.', 'Sub Event start date should be between event start and end date');
            }

            if(($eventdata->end_date < $request->end_date )) {
                return $this->sendError('Validation Error.', 'Sub Event end date should be between event start and end date');
            }
            
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
                    $checkUserJoinedEvent = UserJoinedEvent::where([
                                                ['event_id', $event->id],
                                                ['referee_id', $referee_id]
                                            ])->first();
                    if ($checkUserJoinedEvent) {
                        $user = User::find($checkUserJoinedEvent->user_id);
                        if ($user)
                        {
                            $event->participant = $user;
                        } else {
                            $event->participant = (object)[];
                        }
                    } else {
                        $event->participant = (object)[];
                    }
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

    public function getEventCreatorsEventList($user_id)
    {
        try {
            if (isset($user_id) && !is_null($user_id)) {
                
                $events = Event::where('user_id', $user_id)->get();

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

                    $docfiles = DB::table('files')->where([
                        ['event_id', $event->id],
                        ['type', '=', 'docs']
                    ])->select('url')->get();

                    $event->images =  $imagefiles;
                    $event->vidoes =  $videofiles;
                    $event->docs =  $docfiles;
                    
                    $allevents[] = $event;
                }
                
                return $this->sendResponse($allevents, 'Event Creator event list found.');    
            } else {
                return $this->sendResponse('event creator id found.', ['error'=>'Event Creator event list not found!']);
            }
        } catch (\Exception $e) {
            return $this->sendError('Oops something went wrong.', ['error'=> $e->getMessage()]);
        }
    }
}
