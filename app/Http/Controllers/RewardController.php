<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RewardController extends Controller
{

    public function index()
    {
        $user = auth()->user();
        return view('rewards.index', ['rewards' => $user->rewards, 'userName' => $user->first_name]);
    }
    //
}
