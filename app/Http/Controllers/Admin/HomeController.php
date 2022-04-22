<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\UserTransaction;
use App\Event;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class HomeController
{
    public function index()
    {
        $user = Auth::user();
        $earnedAmount = UserTransaction::orderBy('id', 'DESC')->first();
        $events = Event::count();
        $crossFiters = User::where('user_type', 'CrossFiter')->count();
        $judges = User::where('user_type', 'Judge')->count();
        $activeEvents = Event::where([
            ['status' , 4],
            ['start_date', '<=', Carbon::today()],
            ['end_date', '>=', Carbon::today()]
            ])->count();

        return view('home', compact('user','earnedAmount','events', 'crossFiters', 'judges', 'activeEvents'));
    }
}
