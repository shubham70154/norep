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
        $crossFiter = User::where('user_type', 'CrossFiter')->get(); 

        return view('home', compact('user','earnedAmount','events', 'crossFiter'));
    }
}
