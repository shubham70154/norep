<?php

namespace App\Http\Controllers\Api;

use App\Event;
use App\SubEvent;
use App\User;
use App\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Helpers\Helper;
use App\UserLeaderboard;
use DB, Validator;
use Carbon\Carbon;

class SubEventsApiController extends BaseController
{

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

    public function createSubEvent(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'event_id' =>'required|exists:events,id',
                'name' => 'required',
                'description' => 'required',
                'start_date' => 'required|after_or_equal:today',
                'end_date' => 'after_or_equal:start_date',
                'event_type_id' => 'required|in:events,event_type_id',
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
                'end_time' => $request->end_time
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
    
    public function subEventUpdate(Request $request, $Sub_event_id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'event_id' =>'required|exists:events,id',
                'start_date' => 'after_or_equal:today',
                'end_date' => 'after_or_equal:start_date'
            ]);
        
            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors());       
            }

            if ($request->has('start_date') && $request->has('start_time')) {
                $checkSubEvents = DB::table('sub_events')->where([
                    ['start_date', $request->start_date],
                    ['start_time', $request->start_time],
                    ['event_id', $request->event_id],
                ])->first();
                if($checkSubEvents){
                    return $this->sendError('Validation Error.', 'Sub Event already created with same start date and time');
                }
            }

            $eventdata = DB::table('events')->where([
                ['id', $request->event_id],
                ['status', 1]
            ])->first();
            
            if ($request->has('start_date') && $request->has('end_date')) {
                if(($eventdata->start_date > $request->start_date ||
                    $eventdata->end_date < $request->start_date)
                ) {
                    return $this->sendError('Validation Error.', 'Sub Event start date should be between event start and end date');
                }

                if(($eventdata->end_date < $request->end_date )) {
                    return $this->sendError('Validation Error.', 'Sub Event end date should be between event start and end date');
                }
            }
            
            DB::begintransaction();
            if ($request->has('timer')) {
                $timer = json_encode($request->timer);
                $request->request->add(['timer' => $timer]);
            }
            if ($request->has('scoreboard')) {
                $scoreboard = json_encode($request->scoreboard);
                $request->request->add(['scoreboard' => $scoreboard]);
            }
            
            $subEvent = SubEvent::where('id', $Sub_event_id)->first();

            if ($request->has('images')) {
                $file = File::where([
                    ['event_id', $request->event_id],
                    ['type', 'image'],
                    ['sub_event_id', $Sub_event_id]
                ])->delete();
                $req_images = $request->images;
                $images = [];
                foreach($req_images as $image) {
                    $file = File::create([
                        'url' => $image,
                        'type' => 'image',
                        'event_id' => $request->event_id,
                        'sub_event_id' => $Sub_event_id
                    ]);
                    $images[] = $image;
                }
                $subEvent->images = $images; 
            }

            if ($request->has('videos')) {
                $file = File::where([
                    ['event_id', $request->event_id],
                    ['type', 'video'],
                    ['sub_event_id', $Sub_event_id]
                ])->delete();
                $req_videos = $request->videos;
                $videos = [];
                foreach($req_videos as $video) {
                    $file = File::create([
                        'url' => $video,
                        'type' => 'video',
                        'event_id' => $request->event_id,
                        'sub_event_id' => $Sub_event_id
                    ]);
                    $videos[] = $video;
                }
                $subEvent->videos = $videos; 
            }
            
            if ($request->has('docs')) {
                $file = File::where([
                    ['event_id', $request->event_id],
                    ['type', 'doc'],
                    ['sub_event_id', $Sub_event_id]
                ])->delete();
                $req_docs = $request->docs;
                $docs = [];
                foreach($req_docs as $doc) {
                    $file = File::create([
                        'url' => $doc,
                        'type' => 'doc',
                        'event_id' => $request->event_id,
                        'sub_event_id' => $Sub_event_id
                    ]);
                    $docs[] = $doc;
                }
                $subEvent->docs = $docs; 
            }

            $request->request->remove('images');
            $request->request->remove('videos');
            $request->request->remove('docs');

            SubEvent::where('id', $Sub_event_id)->update($request->all());
            
            DB::commit();
            if (isset($subEvent->scoreboard)) {
                $subEvent->scoreboard = !is_null($subEvent->scoreboard) ? json_decode($subEvent->scoreboard) : null;
            }
            if (isset($subEvent->timer)) {
                $subEvent->timer = !is_null($subEvent->timer) ? json_decode($subEvent->timer) : null;   
            }

            return $this->sendResponse($subEvent, 'Sub event Updated successfully.');
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

}
