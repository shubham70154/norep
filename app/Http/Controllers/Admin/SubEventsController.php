<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Event;
use App\SubEvent;
use Log;
use DB;

class SubEventsController extends Controller
{
    public function subEventList($id)
    {
        $subEvents = SubEvent::all();
        
        return view('admin.sub_events.index', compact('subEvents'));
    }

    public function showSubEvent($id)
    {
        $subEvent = SubEvent::find($id);
        return view('admin.sub_events.show', compact('subEvent'));
    }

    public function getSubEventLeaderBoard($id)
    {   
        $subeventDetail = SubEvent::find($id);
        if ($subeventDetail)
        {
            $scoreboard = json_decode($subeventDetail->scoreboard);

            if ($scoreboard) {
                $header = [];
                if (isset($scoreboard->round) && !is_null($scoreboard->round)) {
                    $header[] = 'Round';
                }
                if (isset($scoreboard->task1) && !is_null($scoreboard->task1)) {
                    $header[] = $scoreboard->task1 ." (Task1)";
                }
                if (isset($scoreboard->task2) && !is_null($scoreboard->task2)) {
                    $header[] = $scoreboard->task2 ." (Task2)";
                }
                if (isset($scoreboard->task3) && !is_null($scoreboard->task3)) {
                    $header[] = $scoreboard->task3 ." (Task3)";
                }
                if (isset($scoreboard->task4) && !is_null($scoreboard->task4)) {
                    $header[] = $scoreboard->task4 ." (Task4)";
                }
                if (isset($scoreboard->task5) && !is_null($scoreboard->task5)) {
                    $header[] = $scoreboard->task5 ." (Task5)";
                }
                if (isset($scoreboard->reps) && !is_null($scoreboard->reps)) {
                    $header[] = 'Reps'. " ($scoreboard->reps)";
                }
                if (isset($scoreboard->time) && !is_null($scoreboard->time)) {
                    $header[] = "Timer" ." ($scoreboard->time)";
                }
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
                    $data[] = $rawData;
                }
                $scoreboard->data = $data;
                $data = [];
            }
            
            $result = [
                'scoreboard' => isset($scoreboard) || !is_null($scoreboard) ? $scoreboard : (object)[],
                'sub_event' => $subeventDetail,
                ];
            //  return $result;
            return view('admin.sub_events.leaderboard', compact('result'));
              
        } 
    }
}
