<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\UserTransaction;
use App\Event;
use Illuminate\Support\Facades\Auth;

class HomeController
{
    public function index()
    {
        $user = Auth::user();
        $earnedAmount = UserTransaction::orderBy('id', 'DESC')->first();
        $events = Event::count();
        $crossFiters = User::where('user_type', 'CrossFiter')->count();
        $judges = User::where('user_type', 'Judge')->count();

        return view('home', compact('user','earnedAmount','events', 'crossFiters', 'judges'));
    }
}
