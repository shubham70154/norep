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
                'sub_event_id' => 'required',
                'user_id' => 'required'
            ]);
        
            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors()->first());       
            }
            
            $subeventDetail = SubEvent::find($request->sub_event_id);
            if ($subeventDetail)
            {
                $imagefiles = DB::table('files')->where([
                    ['event_id', $request->event_id],
                    ['sub_event_id', $request->sub_event_id],
                    ['type', '=', 'image'],
                    ['status', 1]
                ])->select('url')->get();

                $videofiles = DB::table('files')->where([
                    ['event_id', $request->event_id],
                    ['sub_event_id', $request->sub_event_id],
                    ['type', '=', 'video'],
                    ['status', 1]
                ])->select('url')->get();

                $docsfiles = DB::table('files')->where([
                    ['event_id', $request->event_id],
                    ['sub_event_id', $request->sub_event_id],
                    ['type', '=', 'docs'],
                    ['status', 1]
                ])->select('url')->get(); 

                $subeventDetail->images =  $imagefiles;
                $subeventDetail->vidoes =  $videofiles;
                $subeventDetail->docs =  $docsfiles;
                //$subeventDetail->scoreboard = json_decode($subeventDetail->scoreboard);
                $subeventDetail->timer = json_decode($subeventDetail->timer);
                $scoreboard = json_decode($subeventDetail->scoreboard);

                $checkUserLeaderboard =  UserLeaderboard::where([
                                                ['user_id' , $request->user_id],
                                                ['sub_event_id' , $request->sub_event_id],
                                                ['referee_id' , isset($request->referee_id)?$request->referee_id:null],
                                                ['event_id' , $request->event_id],
                                            ])->orderBy('created_at', 'DESC')->first();
                if ($checkUserLeaderboard) {
                    $scoreboard->header = unserialize($checkUserLeaderboard->header);
                    $scoreboard->data = unserialize($checkUserLeaderboard->scoreboard);
                    $athlete_virtual_videos = DB::table('files')->select('id','url', 'type', 'event_id', 'user_leaderboard_id', 'sub_event_id')
                                            ->where([
                                                ['user_leaderboard_id', $checkUserLeaderboard->id],
                                                ['type', '=', 'athlete_virtual_videos'],
                                                ['status', 1]
                                            ])->get();
                    $scoreboard->athlete_virtual_videos = $athlete_virtual_videos;
                    $scoreboard->user_leaderboard_id = $checkUserLeaderboard->id;
                }elseif ($scoreboard) {
                    $header = [];
                    if (isset($scoreboard->round) && !is_null($scoreboard->round)) {
                        $header['round'] = 'Round';
                    }
                    
                    $header['task1'] = isset($scoreboard->task1) ? $scoreboard->task1 : null;
                    $header['task2'] = isset($scoreboard->task2) ? $scoreboard->task2 : null;
                    $header['task3'] = isset($scoreboard->task3) ? $scoreboard->task3 : null;
                    $header['task4'] = isset($scoreboard->task4) ? $scoreboard->task4 : null;
                    $header['task5'] = isset($scoreboard->task5) ? $scoreboard->task5 : null;
                    $header['task6'] = isset($scoreboard->task6) ? $scoreboard->task6 : null;
                    $header['task7'] = isset($scoreboard->task7) ? $scoreboard->task7 : null;
                    $header['task8'] = isset($scoreboard->task8) ? $scoreboard->task8 : null;
                    $header['task9'] = isset($scoreboard->task9) ? $scoreboard->task9 : null;
                    $header['task10'] = isset($scoreboard->task10) ? $scoreboard->task10 : null;
                    
                    if (isset($scoreboard->reps) && !is_null($scoreboard->reps)) {
                        $header['reps'] = 'Reps'. "($scoreboard->reps)";
                    }
                    if (isset($scoreboard->time) && !is_null($scoreboard->time)) {
                        $header['timer'] = "Timer" ."($scoreboard->time)";
                    }
                    if (isset($scoreboard->weight) && !is_null($scoreboard->weight)) {
                        $header['weight'] = "Weight" ."($scoreboard->weight)";
                    }
                    $header['points'] = "Points";
                    $scoreboard->header = $header;
    
                    $data = [];
                    $rawData = [];
                    for ($i = 1; $i <= $scoreboard->round; $i++) {
                        if (isset($scoreboard->round) && !is_null($scoreboard->round)) {
                            $rawData['round'] = $i;
                        }
                        $rawData['task1'] = isset($scoreboard->task1) ? '' : null;
                        $rawData['task2'] = isset($scoreboard->task2) ? '' : null;
                        $rawData['task3'] = isset($scoreboard->task3) ? '' : null;
                        $rawData['task4'] = isset($scoreboard->task4) ? '' : null;
                        $rawData['task5'] = isset($scoreboard->task5) ? '' : null;
                        $rawData['task6'] = isset($scoreboard->task6) ? '' : null;  
                        $rawData['task7'] = isset($scoreboard->task7) ? '' : null;  
                        $rawData['task8'] = isset($scoreboard->task8) ? '' : null;  
                        $rawData['task9'] = isset($scoreboard->task9) ? '' : null;  
                        $rawData['task10'] = isset($scoreboard->task10) ? '' : null;  
                        if (isset($scoreboard->reps) && !is_null($scoreboard->reps)) {
                            $rawData['reps'] = '';
                        }
                        if (isset($scoreboard->time) && !is_null($scoreboard->time)) {
                            $rawData['timer'] = '';
                        }
                        if (isset($scoreboard->weight) && !is_null($scoreboard->weight)) {
                            $rawData['weight'] = '';
                        }
                        $rawData['points'] = '';
                        $data[] = $rawData;
                    }
                    $scoreboard->data = $data;
                    $data = [];
                }
                

                $assignedParticipant = UserJoinedEvent::where([
                    ['event_id', $request->event_id],
                    ['referee_id', $request->referee_id]
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

    public function addUserScoreByReferee(Request $request, $UserLeaderboardId = null)
    {
        try {
            $validator = Validator::make($request->all(), [
                'event_id' => 'required',
                'user_id' => 'required',
                'referee_id' => 'required',
                'sub_event_id' => 'required',
                'header' => 'required',
                'scoreboard' => 'required',
                'total_points' =>  'required',
               // 'athlete_virtual_videos' => 'required_if:event_type_id,1'
            ]);
            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors()->first());       
            }

            $header = serialize($request->header);
            $scoreboard = serialize($request->scoreboard);
            $request->request->add(['header' => $header]);
            $request->request->add(['scoreboard' => $scoreboard]);

            if (!is_null($UserLeaderboardId)) {
                $UserLeaderboard = UserLeaderboard::where("id", $UserLeaderboardId)->first();
                if($UserLeaderboard) {
                    DB::begintransaction();
                    $UserLeaderboard->update($request->all());

                    if ($request->has('athlete_virtual_videos') && $request->event_type_id == 1) {
                        $videos = [];
                        $file = File::where([
                            ['user_leaderboard_id', $UserLeaderboardId],
                            ['type', 'athlete_virtual_videos']
                        ])->delete();
                        foreach($request->athlete_virtual_videos as $url) {
                            $file = File::where("url", $url)->first();
                            if($file) {
                                $file->update([
                                    'status' => 1,
                                    'event_id' => $request->event_id,
                                    'sub_event_id' => $request->sub_event_id,
                                    'user_leaderboard_id' => $UserLeaderboardId 
                                ]);
                            }
                            $videos[] = $url;
                        }
                        $UserLeaderboard->athlete_virtual_videos = $videos;
                    }
                    DB::commit();
                }
            } else {
                DB::begintransaction();
                $UserLeaderboard = UserLeaderboard::create($request->all());

                if ($request->has('athlete_virtual_videos') && $request->event_type_id == 1) {
                    $videos = [];
                    $file = File::where([
                        ['user_leaderboard_id', $UserLeaderboard->id],
                        ['type', 'athlete_virtual_videos']
                    ])->delete();
                    foreach($request->athlete_virtual_videos as $url) {
                        $file = File::where("url", $url)->first();
                        if($file) {
                            $file->update([
                                'status' => 1,
                                'event_id' => $request->event_id,
                                'sub_event_id' => $request->sub_event_id,
                                'user_leaderboard_id' => $UserLeaderboard->id 
                            ]);
                        }
                        $videos[] = $url;
                    }
                    $UserLeaderboard->athlete_virtual_videos = $videos;
                }
                DB::commit();
            }
            $UserLeaderboard->header = unserialize($UserLeaderboard->header);
            $UserLeaderboard->scoreboard = unserialize($UserLeaderboard->scoreboard);

            return $this->sendResponse($UserLeaderboard, 'Scoreboard submitted successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Oops something went wrong.', ['error'=> $e->getMessage(), 
            'line_no'=> $e->getLine()]);
        }
    }

    public function submitFinalUserScoreByReferee(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_leaderboard_id' => 'required',
                'referee_id' => 'required',
                'referee_signature_url' => 'required',
                'athlete_signature_url' => 'required'
            ]);
            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors()->first());       
            }
            DB::begintransaction();
            $UserLeaderboard = UserLeaderboard::where('id', $request->user_leaderboard_id)->update([
                'referee_signature_url' => $request->referee_signature_url,
                'athlete_signature_url' => $request->athlete_signature_url,
                'is_final_submit' => 1,
                'score_given_by' => 'Referee',
                'event_creator_id' => $request->event_creator_id
            ]);
            $UserLeaderboard = UserLeaderboard::find($request->user_leaderboard_id);
            $UserLeaderboard->header = unserialize($UserLeaderboard->header);
            $UserLeaderboard->scoreboard = unserialize($UserLeaderboard->scoreboard);
            DB::commit();
            return $this->sendResponse($UserLeaderboard, 'Final scoreboard submitted successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Oops something went wrong.', ['error'=> $e->getMessage(), 
            'line_no'=> $e->getLine()]);
        }
    }

    public function submitFinalAthleteScoreByEventCreator(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_leaderboard_id' => 'required',
                'event_creator_id' => 'required',
            ]);
            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors()->first());       
            }
            DB::begintransaction();
            $UserLeaderboard = UserLeaderboard::where('id', $request->user_leaderboard_id)->update([
                'is_final_submit' => 1,
                'score_given_by' => 'Event Organiser',
                'event_creator_id' => $request->event_creator_id
            ]);
            $UserLeaderboard = UserLeaderboard::find($request->user_leaderboard_id);
            $UserLeaderboard->header = unserialize($UserLeaderboard->header);
            $UserLeaderboard->scoreboard = unserialize($UserLeaderboard->scoreboard);
            DB::commit();
            return $this->sendResponse($UserLeaderboard, 'Final scoreboard submitted successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Oops something went wrong.', ['error'=> $e->getMessage(), 
            'line_no'=> $e->getLine()]);
        }
    }

}
