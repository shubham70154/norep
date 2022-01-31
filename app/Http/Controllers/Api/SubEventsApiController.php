<?php

namespace App\Http\Controllers\Api;

use App\Event;
use App\EventSpecify;
use App\SubEvent;
use App\User;
use App\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Helpers\Helper;
use App\SubEventSpecify;
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
                return $this->sendError('Validation Error.', $validator->errors()->first());       
            }
            SubEvent::where('id', $request->sub_event_id)->delete();
            UserLeaderboard::where('sub_event_id', $request->sub_event_id)->delete();
            File::where('sub_event_id', $request->sub_event_id)->delete();
            SubEventSpecify::where('sub_event_id', $request->sub_event_id)->delete();
            return $this->sendResponse((object)[], 'Sub Event deleted successfully.');
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
                'start_date' => 'required|date_format:Y-m-d|after_or_equal:today',
                'start_time' => 'date_format:H:i',
                'end_date' => 'date_format:Y-m-d|after_or_equal:start_date',
                'end_time' => 'date_format:H:i',
                'event_type_id' => 'required',
                'location' => 'required',
                'user_id' => 'required',
                'specified_for' => 'required'
            ]);
        
            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors()->first());       
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
                ['deleted_at', null]
            ])->first();
            if($checkSubEvents){
                return $this->sendError('Validation Error.', 'Sub Event already created with same start date and time');
            }

            $eventdata = DB::table('events')->where([
                ['id', $request->event_id],
                ['deleted_at', null]
            ])->first();

            if($eventdata->event_type_id != $request->event_type_id) {
                return $this->sendError('Validation Error.', 'Sub Event Type does not match with Main Event Type.');
            }
            
            if(($eventdata->start_date > $request->start_date ||
                $eventdata->end_date < $request->start_date)
                ) {
                return $this->sendError('Validation Error.', 'Sub Event start date should be between Main Event start and end date');
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
            // update event status 2 means subevent created 
            Event::where('id', $request->event_id)->update(['status'=> 2]);
            if ($request->has('images')) {
                $images = [];
                $req_images = $request->images;
                foreach($req_images as $image) {
                    $file = File::create([
                        'url' => $image,
                        'type' => 'image',
                        'event_id' => $request->event_id,
                        'sub_event_id' => $subEvent->id
                    ]);
                    $images[] = $image;
                }
                $subEvent->images = $images;
            }

            if ($request->has('videos')) {
                $videos = [];
                $req_videos = $request->videos;
                foreach($req_videos as $video) {
                    $file = File::create([
                        'url' => $video,
                        'type' => 'video',
                        'event_id' => $request->event_id,
                        'sub_event_id' => $subEvent->id
                    ]);
                    $videos[] = $video;
                }
                $subEvent->videos = $videos;
            }
            
            // if ($request->has('docs')) {
            //     $docs = [];
            //     $req_docs = $request->docs;
            //     foreach($req_docs as $doc) {
            //         $file = File::create([
            //             'url' => $doc,
            //             'type' => 'doc',
            //             'event_id' => $request->event_id,
            //             'sub_event_id' => $subEvent->id
            //         ]);
            //         $docs[] = $doc;
            //     }
            //     $subEvent->docs = $docs;
            // }

            if ($request->has('specified_for')) {
                $specified = [];
                foreach($request->specified_for as $id) {
                    SubEventSpecify::create([
                        'event_id' => $request->event_id,
                        'sub_event_id' => $subEvent->id,
                        'event_specified_id' => $id
                    ]);
                    $specified[] = $id;
                }
                $subEvent->specified_for = $specified;
            }
            
            DB::commit();
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
                'start_date' => 'date_format:Y-m-d|after_or_equal:today',
                'start_time' => 'date_format:H:i',
                'end_date' => 'date_format:Y-m-d|after_or_equal:start_date',
                'end_time' => 'date_format:H:1'
            ]);
        
            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors()->first());       
            }

            if ($request->has('start_date') && $request->has('start_time')) {
                $checkSubEvents = DB::table('sub_events')->where([
                    ['start_date', $request->start_date],
                    ['start_time', $request->start_time],
                    ['event_id', $request->event_id],
                    ['deleted_at', null]
                ])->first();
                if($checkSubEvents){
                    return $this->sendError('Validation Error.', 'Sub Event already created with same start date and time');
                }
            }

            $eventdata = DB::table('events')->where([
                ['id', $request->event_id],
                ['deleted_at', null]
            ])->first();

            if($eventdata->event_type_id != $request->event_type_id) {
                return $this->sendError('Validation Error.', 'Sub Event Type does not match with Main Event Type.');
            }
            
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
            
            $specified = [];
            $images = [];
            $videos = [];
            $docs = [];

            if ($request->has('images')) {
                $file = File::where([
                    ['event_id', $request->event_id],
                    ['type', 'image'],
                    ['sub_event_id', $Sub_event_id]
                ])->delete();
                $req_images = $request->images;
                foreach($req_images as $image) {
                    $file = File::create([
                        'url' => $image,
                        'type' => 'image',
                        'event_id' => $request->event_id,
                        'sub_event_id' => $Sub_event_id
                    ]);
                    $images[] = $image;
                }
            }

            if ($request->has('videos')) {
                $file = File::where([
                    ['event_id', $request->event_id],
                    ['type', 'video'],
                    ['sub_event_id', $Sub_event_id]
                ])->delete();
                $req_videos = $request->videos;
                foreach($req_videos as $video) {
                    $file = File::create([
                        'url' => $video,
                        'type' => 'video',
                        'event_id' => $request->event_id,
                        'sub_event_id' => $Sub_event_id
                    ]);
                    $videos[] = $video;
                }
            }
            
            if ($request->has('docs')) {
                $file = File::where([
                    ['event_id', $request->event_id],
                    ['type', 'doc'],
                    ['sub_event_id', $Sub_event_id]
                ])->delete();
                $req_docs = $request->docs;
                foreach($req_docs as $doc) {
                    $file = File::create([
                        'url' => $doc,
                        'type' => 'doc',
                        'event_id' => $request->event_id,
                        'sub_event_id' => $Sub_event_id
                    ]);
                    $docs[] = $doc;
                }
            }

            if ($request->has('specified_for')) {
                SubEventSpecify::where([
                    ['event_id', $request->event_id],
                    ['sub_event_id', $subEvent->id]
                ])->delete();
                foreach($request->specified_for as $id) {
                    SubEventSpecify::create([
                        'event_id' => $request->event_id,
                        'sub_event_id' => $subEvent->id,
                        'event_specified_id' => $id
                    ]);
                    $specified[] = $id;
                }
            }

            $request->request->remove('images');
            $request->request->remove('videos');
            $request->request->remove('docs');
            $request->request->remove('specified_for');

            SubEvent::where('id', $Sub_event_id)->update($request->all());

            $subEventData = SubEvent::find($Sub_event_id);

            $subEventData->images =  $images;
            $subEventData->vidoes =  $videos;
            $subEventData->docs =  $docs;
            $subEventData->specified_for =  $specified;
            
            DB::commit();
            if (isset($subEvent->scoreboard)) {
                $subEventData->scoreboard = !is_null($subEventData->scoreboard) ? json_decode($subEventData->scoreboard) : null;
            }
            if (isset($subEvent->timer)) {
                $subEventData->timer = !is_null($subEventData->timer) ? json_decode($subEventData->timer) : null;   
            }

            return $this->sendResponse($subEventData, 'Sub event Updated successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Oops something went wrong.', ['error'=> $e->getMessage(),
            'line'=> $e->getLine()]);
        }
    }

    public function getSubEventList($event_id, $event_specified_id=null)
    {
        try {
            if (!is_null($event_specified_id)) {
                $subeventLists = SubEventSpecify::where([
                    ['event_id', $event_id],
                    ['event_specified_id', $event_specified_id]
                ])->orderBy('created_at', 'DESC')->get();
            } else {
                $subeventLists = SubEventSpecify::where([
                    ['event_id', $event_id]
                ])->orderBy('created_at', 'DESC')->get();
            }

            $allevents = [];
            foreach($subeventLists as $subevent) {
                $subEventData = SubEvent::find($subevent->sub_event_id);

                $eventSpecify = EventSpecify::find($subevent->event_specified_id);
                $imagefiles = DB::table('files')->where([
                    ['event_id', $event_id],
                    ['sub_event_id', $subEventData->id],
                    ['type', '=', 'image']
                ])->select('url')->get();

                $videofiles = DB::table('files')->where([
                    ['event_id', $event_id],
                    ['sub_event_id', $subEventData->id],
                    ['type', '=', 'video']
                ])->select('url')->get();

                $docsfiles = DB::table('files')->where([
                    ['event_id', $event_id],
                    ['sub_event_id', $subEventData->id],
                    ['type', '=', 'doc']
                ])->select('url')->get();
                $subEventData->images =  $imagefiles;
                $subEventData->vidoes =  $videofiles;
                $subEventData->docs =  $docsfiles;
                $subEventData->specified_for =  $eventSpecify;
                $subEventData->scoreboard = !is_null($subEventData->scoreboard) ? json_decode($subEventData->scoreboard) : null;
                $subEventData->timer = !is_null($subEventData->timer) ? json_decode($subEventData->timer) : null;
                $allevents[] = $subEventData;
            }

            return $this->sendResponse($allevents, 'Sub event list get successfully.');
            
        } catch (\Exception $e) {
            return $this->sendError('Oops something went wrong.', ['error'=> $e->getMessage(),
            'line'=> $e->getLine()]);
        }
    }

    public function showSubEventDetails($subEventId)
    {
        try {
            if (isset($subEventId) && !is_null($subEventId)) {
                $subevent = SubEvent::find($subEventId);
                if ($subevent->id) {
                    $imageFiles = DB::table('files')->select('id','url', 'type', 'event_id', 'sub_event_id')
                                    ->where('type','=', 'image')->where('sub_event_id', $subevent->id)->get();
                    $videoFiles = DB::table('files')->select('id','url', 'type', 'event_id', 'sub_event_id')
                                    ->where('type', '=','video')->where('sub_event_id', $subevent->id)->get();
                    $subevent->images = $imageFiles;
                    $subevent->videos = $videoFiles;
                }
                
                $subevent->scoreboard = !is_null($subevent->scoreboard) ? json_decode($subevent->scoreboard) : null;
                $subevent->timer = !is_null($subevent->timer) ? json_decode($subevent->timer) : null;
                return $this->sendResponse($subevent, 'Sub event details get successfully.');    
            } else {
                return $this->sendError('Sub event not found.', ['error'=>'Sub event id not found!']);
            }
        } catch (\Exception $e) {
            return $this->sendError('Oops something went wrong.', ['error'=>'Oops something went wrong!']);
        }
    }

}
