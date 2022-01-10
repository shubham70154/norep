<?php

namespace App\Http\Controllers\Api;


use App\Event;
use App\SubEvent;
use App\User;
use App\File;
use App\UserJoinedEvent;
use App\UserLeaderboard;
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
                //$subeventDetail->scoreboard = json_decode($subeventDetail->scoreboard);
                $subeventDetail->timer = json_decode($subeventDetail->timer);
                $scoreboard = json_decode($subeventDetail->scoreboard);

                if ($scoreboard) {
                    $header = [];
                    if (isset($scoreboard->round) && !is_null($scoreboard->round)) {
                        $header[] = 'Round';
                    }
                    if (isset($scoreboard->task1) && !is_null($scoreboard->task1)) {
                        $header[] = $scoreboard->task1;
                    }
                    if (isset($scoreboard->task2) && !is_null($scoreboard->task2)) {
                        $header[] = $scoreboard->task2;
                    }
                    if (isset($scoreboard->task3) && !is_null($scoreboard->task3)) {
                        $header[] = $scoreboard->task3;
                    }
                    if (isset($scoreboard->task4) && !is_null($scoreboard->task4)) {
                        $header[] = $scoreboard->task4;
                    }
                    if (isset($scoreboard->task5) && !is_null($scoreboard->task5)) {
                        $header[] = $scoreboard->task5;
                    }
                    if (isset($scoreboard->reps) && !is_null($scoreboard->reps)) {
                        $header[] = 'Reps'. "($scoreboard->reps)";
                    }
                    if (isset($scoreboard->time) && !is_null($scoreboard->time)) {
                        $header[] = "Timer" ."($scoreboard->time)";
                    }
                    $header[] = "Points";
                    $scoreboard->header = $header;
    
                    $data = [];
                    $rawData = [];
                    for ($i = 1; $i <= $scoreboard->round; $i++) {
                        if (isset($scoreboard->round) && !is_null($scoreboard->round)) {
                            $rawData['round'] = $i;
                        }
                        if (isset($scoreboard->task1) && !is_null($scoreboard->task1)) {
                            $rawData['task1'] = '';
                        }
                        if (isset($scoreboard->task2) && !is_null($scoreboard->task2)) {
                            $rawData['task2'] = '';
                        }
                        if (isset($scoreboard->task3) && !is_null($scoreboard->task3)) {
                            $rawData['task3'] = '';
                        }
                        if (isset($scoreboard->task4) && !is_null($scoreboard->task4)) {
                            $rawData['task4'] = '';
                        }
                        if (isset($scoreboard->task5) && !is_null($scoreboard->task5)) {
                            $rawData['task5'] = '';
                        }
                        if (isset($scoreboard->reps) && !is_null($scoreboard->reps)) {
                            $rawData['reps'] = '';
                        }
                        if (isset($scoreboard->time) && !is_null($scoreboard->time)) {
                            $rawData['timer'] = '';
                        }
                        $rawData['points'] = '';
                        $data[] = $rawData;
                    }
                    $scoreboard->data = $data;
                    $data = [];
                }
                

                $assignedParticipant = UserJoinedEvent::where([
                    ['event_id', $request->event_id],
                    ['referee_id', $request->user_id]
                ])->first();

                if ($assignedParticipant) {
                    $userDetails = User::find($assignedParticipant->user_id);
                }
                $result = [
                    'scoreboard' => isset($scoreboard) || !is_null($scoreboard) ? $scoreboard : (object)[],
                    'sub_event' => $subeventDetail,
                    'participant' => isset($userDetails) ? $userDetails : (object)[]];
                return $this->sendResponse($result, 'Result fetch successfully.');
                
            } else {
                return $this->sendResponse((object)[], "No referees are assigned to this event");
            }
        } catch (\Exception $e) {
            return $this->sendError('Oops something went wrong.', ['error'=> $e->getMessage(), 
            'line_no'=> $e->getLine()]);
        }
    }

    public function addUserScoreByReferee(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'event_id' => 'required',
                'user_id' => 'required',
                'referee_id' => 'required',
                'sub_event_id' => 'required',
                'header' => 'required',
                'scoreboard' => 'required',
                'total_points' =>  'required'
            ]);
            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors());       
            }

            DB::begintransaction();
            $UserLeaderboard = UserLeaderboard::create($request->all());
            DB::commit();
            return $this->sendResponse($UserLeaderboard, 'Scoreboard submitted successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Oops something went wrong.', ['error'=> $e->getMessage(), 
            'line_no'=> $e->getLine()]);
        }

    }

}
