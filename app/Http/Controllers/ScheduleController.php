<?php

namespace App\Http\Controllers;

use App\DataTables\WorkoutDataTable;
use App\DataTables\WorkoutScheduleDataTable;
use App\Helpers\AuthHelper;
use App\Models\Workout;
use App\Models\WorkoutSchedule;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {

        $workoutId = $request->input('workout_id', null);
        $workout = null;

        if (!is_null($workoutId)) {
            $workout = Workout::find($workoutId);
            // Optionally, handle the case where $workout is null if the ID does not exist
        }

        $dataTable = new WorkoutScheduleDataTable($workout);


        $pageTitle = __('message.list_form_title',['form' => __('message.schedule')] );
        $auth_user = AuthHelper::authSession();
        if( !$auth_user->can('workout-list') ) {
            $message = __('message.permission_denied_for_account');
            return redirect()->back()->withErrors($message);
        }
        $assets = ['data-table'];

        $headerAction = $auth_user->can('workout-add') ? '<a href="'.route('schedule.create').'" class="btn btn-sm btn-primary" role="button">'.__('message.add_form_title', [ 'form' => __('message.workout')]).'</a>' : '';

        return $dataTable->render('global.datatable', compact('pageTitle', 'auth_user', 'assets', 'headerAction'));


    }
    public function calendar()
    {

        $workouts = Workout::active()->get();
        return view('schedule.calendar', compact('workouts'));

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

    public function destroy($id)
    {
        if( !auth()->user()->can('workout-delete') ) {
            $message = __('message.permission_denied_for_account');
            return redirect()->back()->withErrors($message);
        }

        $workout = WorkoutSchedule::findOrFail($id);
        $this->authorize('delete', $workout);

        $status = 'errors';
        $message = __('message.not_found_entry', ['name' => __('message.schedule')]);

        if($workout != '') {
            $workout->delete();
            $status = 'success';
            $message = __('message.delete_form', ['form' => __('message.schedule')]);
        }

        if(request()->ajax()) {
            return response()->json(['status' => true, 'message' => $message ]);
        }

        return redirect()->back()->with($status,$message);
    }
}
