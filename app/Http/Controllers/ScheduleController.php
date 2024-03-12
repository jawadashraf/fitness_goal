<?php

namespace App\Http\Controllers;

use App\Models\WorkoutSchedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index()
    {

        return view('schedule.index');

    }

    public function getEvents()
    {
        $schedules = WorkoutSchedule::where('user_id', auth()->id());
        return response()->json($schedules);
    }
}
