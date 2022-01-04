<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\UserJoinedEvent;
use App\Event;
use App\User;
use DB,Log;

class HelpSupportsController extends Controller
{
    public function index()
    {
        $helpSupports = DB::table('help_supports')
            ->join('users', 'help_supports.user_id', '=', 'users.id')
            ->select('users.name', 'help_supports.*')
            ->get();

        return view('admin.help_supports.index', compact('helpSupports'));
    }

    public function show($id)
    {
        if(isset($id) && !is_null($id)) {
          $result =  DB::table('help_supports')
          ->join('users', 'help_supports.user_id', '=', 'users.id')
          ->select('users.name', 'help_supports.*')
          ->where('help_supports.id', $id)->first();   
        }
        return view('admin.help_supports.show', compact('result'));
    }

}
