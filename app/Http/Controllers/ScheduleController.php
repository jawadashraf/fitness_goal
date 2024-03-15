<?php

namespace App\Http\Controllers;

use App\DataTables\WorkoutDataTable;
use App\DataTables\WorkoutScheduleDataTable;
use App\Helpers\AuthHelper;
use App\Models\Goal;
use App\Models\GoalAchievement;
use App\Models\GoalProgress;
use App\Models\Workout;
use App\Models\WorkoutSchedule;
use App\Models\WorkoutScheduleProgress;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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

        $headerAction = $auth_user->can('workout-add') ? '<a href="'.route('schedule.create').'" class="btn btn-sm btn-primary" role="button">'.__('message.add_form_title', [ 'form' => __('message.schedule')]).'</a>' : '';

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

    public function create()
    {
        if( !auth()->user()->can('workout-add') ) {
            $message = __('message.permission_denied_for_account');
            return redirect()->back()->withErrors($message);
        }
        $pageTitle = __('message.add_form_title',[ 'form' => __('message.schedule')]);

        return view('schedule.form', compact('pageTitle'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            // validation rules
            'workout_id' => 'required',
            'start' => 'required|date',
            'end' => 'required|date|after:start',
            // add other fields as necessary
        ]);


        $start = Carbon::parse($data['start']);
        $end = Carbon::parse($data['end']);

        if (!$start->isSameDay($end)) {
            // If not on the same day, redirect back with an error
            return back()->withErrors(['end' => 'The end time must be on the same day as the start time.'])->withInput();
        }

        $workout = Workout::find($request['workout_id']);
        $request['title'] = $workout->title;

        $workoutSchedule = WorkoutSchedule::create($request->all());

        $workoutScheduleId = $workoutSchedule->id;
        $workoutId = $workoutSchedule->workout_id;

        DB::transaction(function () use ($workoutScheduleId, $workoutId) {
            $workoutSchedule = WorkoutSchedule::find($workoutScheduleId);
            $workout = Workout::find($workoutId);

            $workoutDayExercises = $workout->exercises; // Assuming you have a 'exercises' relationship defined in your Workout model

            $exercises = collect();
            foreach ($workoutDayExercises as $workoutDayExercise) {
                // Access the Exercise model from the WorkoutDayExercise model
                $exercise = $workoutDayExercise->exercise;
                $exercises->push($exercise);
            }

            foreach ($exercises as $exercise) {
                WorkoutScheduleProgress::create([
                    'workout_schedule_id' => $workoutSchedule->id,
                    'exercise_id' => $exercise->id,
                    'progress' => 0, // Initial progress, adjust as needed
                ]);
            }
        });



        return redirect()->route('schedule.index', ['workout_id' => $workoutSchedule->workout_id])->withSuccess(__('message.save_form', ['form' => __('message.schedule')]));

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

        $workoutScheduleId = $workoutSchedule->id;
        $workoutId = $workoutSchedule->workout_id;
        DB::transaction(function () use ($workoutScheduleId, $workoutId) {
            $workoutSchedule = WorkoutSchedule::find($workoutScheduleId);
            $workout = Workout::find($workoutId);

            $workoutDayExercises = $workout->exercises; // Assuming you have a 'exercises' relationship defined in your Workout model

            $exercises = collect();
            foreach ($workoutDayExercises as $workoutDayExercise) {
                // Access the Exercise model from the WorkoutDayExercise model
                $exercise = $workoutDayExercise->exercise;
                $exercises->push($exercise);
            }

            foreach ($exercises as $exercise) {
                WorkoutScheduleProgress::create([
                    'workout_schedule_id' => $workoutSchedule->id,
                    'exercise_id' => $exercise->id,
                    'progress' => 0, // Initial progress, adjust as needed
                ]);
            }
        });

        // Return a response
        return response()->json(['message' => 'WorkoutSchedule created successfully', 'workoutSchedule' => $workoutSchedule]);
    }

    public function edit($id)
    {
        if( !auth()->user()->can('workout-edit') ) {
            $message = __('message.permission_denied_for_account');
            return redirect()->back()->withErrors($message);
        }

        $data = WorkoutSchedule::with('workout_schedule_progress.exercise')->findOrFail($id);
        $this->authorize('update', $data);

        $pageTitle = __('message.update_form_title',[ 'form' => __('message.schedule') ]);

        return view('schedule.form', compact('data','id','pageTitle'));
    }


    public function update(Request $request, $id)
    {
        if( !auth()->user()->can('goal-edit') ) {
            $message = __('message.permission_denied_for_account');
            return redirect()->back()->withErrors($message);
        }

        $data = $request->validate([
            'start' => 'required|date',
            'end' => 'required|date|after:start',
            'schedule_progress.*' => 'required|numeric|min:0',
        ]);


        $start = Carbon::parse($data['start']);
        $end = Carbon::parse($data['end']);

        if (!$start->isSameDay($end)) {
            // If not on the same day, redirect back with an error
            return back()->withErrors(['end' => 'The end time must be on the same day as the start time.'])->withInput();
        }

        $workoutSchedule = WorkoutSchedule::findOrFail($id);


        $workoutSchedule->fill($request->all())->update();

        $today = Carbon::today();

        DB::transaction(function () use ($request, $workoutSchedule, $today) {
            foreach ($request->schedule_progress as $progressId => $progressValue) {
                $scheduleProgress = WorkoutScheduleProgress::with('exercise.goal_type')
                    ->where('id', $progressId)
                    ->where('workout_schedule_id', $workoutSchedule->id)
                    ->first();

                if ($scheduleProgress) {
                    $scheduleProgress->update([

                        'progress' => $progressValue,
                    ]);

                    // Check for an active goal for the exercise's goal type
                    $activeGoals = Goal::where('goal_type_id', $scheduleProgress->exercise->goal_type_id)
                        ->where('status', 'active')
                        ->whereDate('start_date', '<=', $today)
                        ->whereDate('end_date', '>=', $today)
                        ->get();

                    if ($activeGoals) {
                        // Update or create goal progress for today
                        foreach ($activeGoals as $activeGoal){

                            $goalProgress = GoalProgress::updateOrCreate([
                                'goal_id' => $activeGoal->id,
                                'date' => $today,
                            ], [
                                'progress_value' => DB::raw("progress_value + $progressValue"),
                                'user_id' => auth()->id()
                            ]);

                            $goalProgress->refresh();

                            if ($goalProgress->progress_value >= $activeGoal->tagret_value) {
                                // Check if an achievement for this goal has already been recorded today
                                $achievementExists = GoalAchievement::where('goal_id', $activeGoal->id)
                                    ->where('user_id', auth()->id())
                                    ->whereDate('achieved_at', '=', $today)
                                    ->exists();

                                if (!$achievementExists) {
                                    // If no achievement has been recorded today, record this achievement
                                    GoalAchievement::create([
                                        'user_id'=>auth()->id(),
                                        'goal_id' => $activeGoal->id,
                                        'achieved_at' => $today, // Record the date (time part is 00:00:00)
                                    ]);

                                    // Handle any associated rewards here, as necessary
                                }
                            }

                        }
                    }
                }
            }
        });


        if(auth()->check()){
            return redirect()->route('schedule.index', ['workout_id'=>$workoutSchedule->workout_id])->withSuccess(__('message.update_form',['form' => __('message.goal')]));
        }
        return redirect()->back()->withSuccess(__('message.update_form',['form' => __('message.goal') ] ));

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
