<?php

namespace App\Http\Controllers;

use App\DataTables\WorkoutDataTable;
use App\DataTables\WorkoutScheduleDataTable;
use App\Helpers\AuthHelper;
use App\Models\Goal;
use App\Models\GoalAchievement;
use App\Models\GoalProgress;
use App\Models\Reward;
use App\Models\User;
use App\Models\Workout;
use App\Models\WorkoutSchedule;
use App\Models\WorkoutScheduleProgress;
use App\Notifications\CommonNotification;
use App\Notifications\DatabaseNotification;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Mail;

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

    public function update_event_on_drop(Request $request, $id)
    {
        $schedule = WorkoutSchedule::findOrFail($id);

        $schedule->update([
            'start' => Carbon::parse($request->input('start_date'))->setTimezone('UTC'),
            'end' => Carbon::parse($request->input('end_date'))->setTimezone('UTC'),
        ]);

        return response()->json(['message' => 'Event moved successfully']);
    }

    public function resize(Request $request, $id)
    {
        $schedule = WorkoutSchedule::findOrFail($id);

        $newEndDate = Carbon::parse($request->input('end_date'))->setTimezone('UTC');
        $schedule->update(['end' => $newEndDate]);

        return response()->json(['message' => 'Event resized successfully.']);
    }

    public function getEvents()
    {
        $schedules = WorkoutSchedule::with(['workout.exercises.exercise.goal_type.goals'])->where('user_id', auth()->id())->get();
        $schedules->map(function ($schedule) {
            // For each schedule, collect goals from all exercises' goal types
            $goalsList = collect();
            foreach ($schedule->workout->exercises as $exercise) {
                $goals = $exercise->exercise->goal_type->goals->pluck('title'); // Collecting goal names
                $goalsList = $goalsList->merge($goals);
            }

            // Make the list unique, reset keys, and check if it's empty
            $uniqueGoals = $goalsList->unique()->values()->all();

            // If there are no goals, set a default message
            $schedule->goals = empty($uniqueGoals) ? ['no goals attached'] : $uniqueGoals;

            // Add URL attribute for editing the schedule, using the schedule's id
            $schedule->url = route('schedule.edit', ['id' => $schedule->id]);


            return $schedule;
        });
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

        session()->flash('schedule', "You have created a workout schedule");
        //Notify user for Reward
        $notification_data = [
            'id' => -1,
            'push_notification_id' => -1,
            'type' => 'schedule',
            'subject' => 'Workout:' . $workout->title,
            'message' => 'You have created the workout schedule:' . $workout->title,
            'image' => null
        ];
        $user = auth()->user();

        $user->notify(new CommonNotification($notification_data['type'], $notification_data));
        $user->notify(new DatabaseNotification($notification_data));


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

    public function updateOnDrag(Request $request, $id)
    {
        $schedule = WorkoutSchedule::findOrFail($id);

        $schedule->update([
            'start' => Carbon::parse($request->input('start_date'))->setTimezone('UTC'),
            'end' => Carbon::parse($request->input('end_date'))->setTimezone('UTC'),
        ]);

        return response()->json(['message' => 'Event updated successfully']);
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
                                    $this->recordGoalAchievement($activeGoal->id);

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

    public function recordGoalAchievement($goalId)
    {
        $achievement = GoalAchievement::create([
            'user_id' => auth()->id(),
            'goal_id' => $goalId,
            'achieved_at' => now(),
        ]);
        session()->flash('achievement', "You have achieved a goal");

        $goal = Goal::find($goalId);
        $user = auth()->user();
        //Notify User for Goal Achievement

        $notification_data = [
            'id' => -1,
            'push_notification_id' => -1,
            'type' => 'achievement',
            'subject' => 'Achievement:' . $goal->title,
            'message' => 'You have achieved the goal:' . $goal->title,
            'image' => null
        ];
        $user->notify(new CommonNotification($notification_data['type'], $notification_data));
        $user->notify(new DatabaseNotification($notification_data));

//        Mail::to($user->email)->send(new \App\Mail\AchievementEmail($user, $goal->title));

        // Check for reward threshold
        $this->checkForRewards($achievement->user, $achievement->goal);
    }

    public function checkForRewards(User $user, Goal $goal)
    {
        $achievementCount = $user->goal_achievements()
            ->where('goal_id', $goal->id)
            ->count();

        $rewards = Reward::where('goal_type_id', $goal->goal_type_id)->get();

        foreach ($rewards as $reward) {
            // Check if this is the exact achievement that meets the threshold
            if ($achievementCount == $reward->threshold) {
                // Check if the reward has not been granted before
                $alreadyGranted = $user->rewards()
                    ->where('reward_id', $reward->id)
                    ->exists();

                if (!$alreadyGranted) {
                    // Grant the reward
                    $user->rewards()->attach($reward->id, ['granted_at' => now()]);
                    session()->flash('reward', "You have achieved a reward");
                    //Notify user for Reward
                    $notification_data = [
                        'id' => -1,
                        'push_notification_id' => -1,
                        'type' => 'reward',
                        'subject' => 'Reward:' . $reward->title,
                        'message' => 'You have achieved the reward:' . $reward->title,
                        'image' => null
                    ];
                    $user->notify(new CommonNotification($notification_data['type'], $notification_data));
                    $user->notify(new DatabaseNotification($notification_data));
//                    Mail::to($user->email)->send(new \App\Mail\RewardEmail($user, $reward->title));
                }
            }
        }
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
