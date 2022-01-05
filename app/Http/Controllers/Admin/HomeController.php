<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Illuminate\Support\Facades\Auth;

class HomeController
{
    public function index()
    {
        $user = Auth::user(); 

        return view('home', compact('user'));
    }
}
