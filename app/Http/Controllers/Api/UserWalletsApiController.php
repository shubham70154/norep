<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Event;
use App\SubEvent;
use App\User;
use App\File;
use App\UserJoinedEvent;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Helpers\Helper;
use App\Http\Requests\Request as RequestsRequest;
use DB, Validator, Illuminate\Support\Carbon;

class UserWalletsApiController extends BaseController
{
    public function getUserWallet($user_id)
    {
        try {
            if (isset($user_id) && !is_null($user_id))
            {
                $userEvents = Event::where([
                    ['user_id', $user_id],
                    ['status', 1]
                ])->orderBy('start_date', 'DESC')->pluck('id')->toArray();

                $eventsAmount = UserJoinedEvent::whereIn('event_id', $userEvents)
                ->select('event_id','name', DB::raw('sum(amount) as total'))
                ->groupBy('event_id')
                ->get();

                $totalAmount = 0;
                foreach($eventsAmount as $event)
                {
                    $totalAmount = $totalAmount + $event->total;
                }
                $result = ['event_amount' => $eventsAmount, 'total_amount' => $totalAmount];
                return $this->sendResponse($result, 'LeaderBoard fetch successfully.');
                //return $result;
            } else {
                return $this->sendError('User not found.', ['error'=>'User id not found!']);
            }
            
        } catch (\Exception $e) {
            return $this->sendError('Oops something went wrong.', ['error'=> $e->getMessage()]);
        }
    }

}
