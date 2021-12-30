<?php

namespace App\Http\Controllers\Api;


use App\Event;
use App\SubEvent;
use App\User;
use App\File;
use App\UserJoinedEvent;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use DB, Validator, Illuminate\Support\Carbon;

class RefereesApiController extends BaseController
{
    public function refereeGetSubeventDetails(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'event_id' => 'required',
                'user_id' => 'required',
                'sub_event_id' => 'required'
            ]);
        
            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors());       
            }
            
            $subeventDetail = SubEvent::findOrFail($request->sub_event_id);
            if ($subeventDetail)
            {
                $imagefiles = DB::table('files')->where([
                    ['event_id', $request->event_id],
                    ['sub_event_id', $request->sub_event_id],
                    ['type', '=', 'image']
                ])->select('url')->get();

                $videofiles = DB::table('files')->where([
                    ['event_id', $request->event_id],
                    ['sub_event_id', $request->sub_event_id],
                    ['type', '=', 'video']
                ])->select('url')->get();

                $docsfiles = DB::table('files')->where([
                    ['event_id', $request->event_id],
                    ['sub_event_id', $request->sub_event_id],
                    ['type', '=', 'docs']
                ])->select('url')->get();

                $subeventDetail->images =  $imagefiles;
                $subeventDetail->vidoes =  $videofiles;
                $subeventDetail->docs =  $docsfiles;
                $subeventDetail->scoreboard = json_decode($subeventDetail->scoreboard);
                $subeventDetail->timer = json_decode($subeventDetail->timer);
                $scoreboard = $subeventDetail->scoreboard;

                $assignedParticipant = UserJoinedEvent::where([
                    ['event_id', $request->event_id],
                    ['referee_id', $request->user_id]
                ])->first();

                if ($assignedParticipant) {
                    $userDetails = User::find($assignedParticipant->user_id);
                }
                $result = [
                    'scoreboard' => $scoreboard,
                    'sub_event' => $subeventDetail,
                    'participant' => isset($userDetails) ? $userDetails : (object)[]];
                return $this->sendResponse($result, 'Result fetch successfully.');
                
            } else {
                return $this->sendResponse((object)[], "No referees are assigned to this event");
            }
        } catch (\Exception $e) {
            return $this->sendError('Oops something went wrong.', ['error'=> $e->getMessage()]);
        }
    }

}
