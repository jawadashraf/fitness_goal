<?php

namespace App\Http\Controllers;

use App\Models\Workout;
use App\Models\WorkoutSchedule;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index()
    {

        $workouts = Workout::active()->get();
        return view('schedule.index', compact('workouts'));

    }

    public function getEvents()
    {
        $schedules = WorkoutSchedule::where('user_id', auth()->id())->get();
        return response()->json($schedules);
    }

    public function createFromDrop(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'workoutId' => 'required|integer',
            'date' => 'required|date',
            'title' => 'required',
            // Validate other fields as necessary
        ]);

        $start = new Carbon($validated['date']);
        $end = $start->copy()->addHour(); // Add one hour to the start time to get the end time

        // Create the WorkoutSchedule using the validated data
        $workoutSchedule = WorkoutSchedule::create([
            'user_id' => auth()->id() ,
            'title' => $validated['title'],
            'workout_id' => $validated['workoutId'],
            'start' => $start,
            'end' => $end,
            // Set other fields as necessary
        ]);

        // Return a response
        return response()->json(['message' => 'WorkoutSchedule created successfully', 'workoutSchedule' => $workoutSchedule]);
    }


    public function deleteEvent($id)
    {

        try {
            $schedule = WorkoutSchedule::findOrFail($id);

            // This will throw an exception if the user is not authorized
            $this->authorize('delete', $schedule);

            $schedule->delete();

            return response()->json(['message' => 'Event deleted successfully']);
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            // Return a 403 Forbidden response if the user is not authorized
            return response()->json(['message' => 'Unauthorized'], 403);
        } catch (\Exception $e) {
            // Handle other possible exceptions
            return response()->json(['message' => 'An error occurred'], 500);
        }

    }
}
