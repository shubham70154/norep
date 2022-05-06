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
        $subEvents = SubEvent::find($id);
        
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
                if (isset($scoreboard->task6) && !is_null($scoreboard->task6)) {
                    $header[] = $scoreboard->task6 ." (Task6)";
                }
                if (isset($scoreboard->task7) && !is_null($scoreboard->task7)) {
                    $header[] = $scoreboard->task7 ." (Task7)";
                }
                if (isset($scoreboard->task8) && !is_null($scoreboard->task8)) {
                    $header[] = $scoreboard->task8 ." (Task8)";
                }
                if (isset($scoreboard->task9) && !is_null($scoreboard->task9)) {
                    $header[] = $scoreboard->task9 ." (Task9)";
                }
                if (isset($scoreboard->task10) && !is_null($scoreboard->task10)) {
                    $header[] = $scoreboard->task10 ." (Task10)";
                }
                if (isset($scoreboard->reps) && !is_null($scoreboard->reps)) {
                    $header[] = 'Reps'. " ($scoreboard->reps)";
                }
                if (isset($scoreboard->time) && !is_null($scoreboard->time)) {
                    $header[] = "Timer" ." ($scoreboard->time)";
                }
                if (isset($scoreboard->weight) && !is_null($scoreboard->weight)) {
                    $header[] = "Weight" ." ($scoreboard->weight)";
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
                    if (isset($scoreboard->task6) && !is_null($scoreboard->task6)) {
                        $rawData['task6'] = '';
                    }
                    if (isset($scoreboard->task7) && !is_null($scoreboard->task7)) {
                        $rawData['task7'] = '';
                    }
                    if (isset($scoreboard->task8) && !is_null($scoreboard->task8)) {
                        $rawData['task8'] = '';
                    }
                    if (isset($scoreboard->task9) && !is_null($scoreboard->task9)) {
                        $rawData['task9'] = '';
                    }
                    if (isset($scoreboard->task10) && !is_null($scoreboard->task10)) {
                        $rawData['task10'] = '';
                    }
                    if (isset($scoreboard->reps) && !is_null($scoreboard->reps)) {
                        $rawData['reps'] = '';
                    }
                    if (isset($scoreboard->time) && !is_null($scoreboard->time)) {
                        $rawData['timer'] = '';
                    }
                    if (isset($scoreboard->weight) && !is_null($scoreboard->weight)) {
                        $rawData['weight'] = '';
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
