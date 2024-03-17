<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use App\Models\GoalProgress;
use App\Models\GoalType;
use App\Models\UnitType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Equipment;
use App\Models\Level;
use App\Models\WorkoutType;
use App\Models\Workout;
use App\Models\Diet;
use App\Models\CategoryDiet;
use Illuminate\Support\Facades\App;
use App\Models\BodyPart;
use App\Models\Exercise;
use App\Models\Tags;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use App\Models\Product;
use App\Models\Package;
use App\Models\ProductCategory;
use App\Helpers\AuthHelper;
use App\Models\AppSetting;

class HomeController extends Controller
{
    /*
     * Dashboard Pages Routs
     */
    public function index(Request $request)
    {
        $assets = ['chart', 'animation'];
        $auth_user = AuthHelper::authSession();
        $data['dashboard'] = [
            'total_equipment'   => Equipment::count(),
            'total_level'       => Level::count(),
            'total_bodypart'    => BodyPart::count(),
            'total_workouttype' => WorkoutType::count(),
            'total_exercise'    => Exercise::count(),
            'total_workout'     => Workout::count(),
        ];


        if(auth()->user()->user_type == 'user') {
            $activeGoals = Goal::where('status', 'ACTIVE')->get();

            $goalsProgressData = $activeGoals->map(function ($goal) {
                $todaysProgress = GoalProgress::where('goal_id', $goal->id)
                    ->whereDate('date', Carbon::today())
                    ->sum('progress_value');

                return [
                    'category' => $goal->title,
                    'value' => $todaysProgress,
                    'full' => 100, // Assuming the full scale is 100
                    'columnSettings' => [
                        'fill' => '#someColor' // Optional: Define color or other settings
                    ]
                ];
            })->toArray();

            $goalProgressPerWeek = [];// $this->getUserGoalProgressForWeek($auth_user->id, $activeGoals->first()->id);
            $weeklyProgressForAllGoals = $this->getWeeklyProgressForAllGoals();
            return view('dashboards.user_dashboard', compact('assets',
                'data', 'auth_user', 'goalsProgressData','goalProgressPerWeek', 'weeklyProgressForAllGoals'));
        }
        else {
            $data['exercise'] = Exercise::orderBy('id', 'desc')->take(10)->get();
            $data['workout'] = Workout::orderBy('id', 'desc')->take(10)->get();
            return view('dashboards.dashboard', compact('assets', 'data', 'auth_user'));
        }
    }

    public function getUserGoalProgressForWeek($userId, $goalId)
    {
        $now = now();
        $startOfWeek = $now->copy()->startOfWeek(Carbon::MONDAY);
        $endOfWeek = $now->copy()->endOfWeek(Carbon::SUNDAY);

        // Create a collection of all dates in the week
        $dates = collect();
        for ($date = $startOfWeek->copy(); $date->lte($endOfWeek); $date->addDay()) {
            $dates->push($date->format('Y-m-d'));
        }

        // Fetch progress data grouped by date
        $progressData = GoalProgress::where('user_id', $userId)
            ->where('goal_id', $goalId)
            ->whereBetween('date', [$startOfWeek, $endOfWeek])
            ->get()
            ->groupBy(function ($item) {
                return Carbon::parse($item->date)->format('Y-m-d');
            });

        // Merge progress data with the list of all days, setting missing days to zero
        $formattedData = $dates->map(function ($date) use ($progressData) {
            // Check if there's an entry for the given date
            $timestamp = Carbon::createFromFormat('Y-m-d', $date)->startOfDay()->valueOf();
            if ($progressData->has($date)) {
                $progress = $progressData->get($date)->first();


                return [
                    'date' => $timestamp,
                    'value' => $progress->progress_value,
                ];
            } else {
                return [
                    'date' => $timestamp,
                    'value' => 0,
                ];
            }
        })->toArray();

        return $formattedData;
    }

    public function getWeeklyProgressForAllGoals() {
        $goals = Goal::with('progress')->where('status', 'ACTIVE')->get(); // Assuming Goal is your model name

        $now = Carbon::now();
        $startOfWeek = $now->copy()->startOfWeek(Carbon::MONDAY);
        $endOfWeek = $now->copy()->endOfWeek(Carbon::SUNDAY);


        $allGoalsProgressData = [];

        foreach ($goals as $goal) {
            $dates = collect();
            for ($date = $startOfWeek->copy(); $date->lte($endOfWeek); $date->addDay()) {
                for ($date = $startOfWeek->copy(); $date->lte($endOfWeek); $date->addDay()) {
                    $dates->push($date->format('Y-m-d'));
                }
            }

            $progressData = $goal->progress()
                ->whereBetween('date', [$startOfWeek, $endOfWeek])
                ->get();
            $progressData = $progressData->keyBy(function ($item) {
                // Assuming 'date' is a Carbon instance or a date string in 'Y-m-d H:i:s' format
                return Carbon::parse($item->date)->format('Y-m-d');
            });
            // Map existing progress data onto the dates
//            foreach ($progressData as $progress) {
//                $progressDate = Carbon::parse($progress->date)->format('Y-m-d');
//                $dates[$progressDate] = [
//                    'date' => Carbon::parse($progress->date)->timestamp * 1000, // Unix timestamp in milliseconds
//                    'value' => $progress->progress_value
//                ];
//            }
//            dd($progressData, $dates);
            $formattedData = $dates->map(function ($date) use ($progressData) {
                // Check if there's an entry for the given date


                if ($progressData->has($date)) {

                    $progress = $progressData->get($date);
                    $timestamp = Carbon::createFromFormat('Y-m-d', $date)->startOfDay()->valueOf();

                    return [
                        'date' => $timestamp,
                        'value' => (float) $progress->progress_value,
                    ];
                } else {
                    $timestamp = Carbon::createFromFormat('Y-m-d', $date)->startOfDay()->valueOf();
                    return [
                        'date' => $timestamp,
                        'value' => 0,
                    ];
                }
            })->toArray();

            $allGoalsProgressData[$goal->title] = array_values($formattedData);
        }
        return $allGoalsProgressData;
    }


    public function changeStatus(Request $request)
    {
        if(env('APP_DEMO')){
            $message = __('message.demo_permission_denied');
            $response = [
                'status'    => false,
                'message'   => $message
            ];
            return json_custom_response($response);
        }

        $type = $request->type;
        $message_form = "";
        $message = __('message.update_form',['form' => __('message.status')]);
        switch ($type) {
            case 'role':
                    $role = Role::find($request->id);
                    $role->status = $request->status;
                    $role->save();
                    break;
            default:
                    $message = 'error';
                break;
        }

        if($message_form != null){
            $message =  __('message.added_form',['form' => $message_form ]);
            if($request->status == 0){
                $message = __('message.remove_form',['form' => $message_form ]);
            }
        }

        return json_custom_response(['message'=> $message , 'status' => true]);
    }

    public function removeFile(Request $request)
    {
        $type = $request->type;
        $data = null;

        switch ($type) {
            case 'equipment_image':
                $data = Equipment::find($request->id);
                $message = __('message.msg_removed',[ 'name' => __('message.equipment') ]);
                break;
            case 'workout_image':
                $data = Workout::find($request->id);
                $message = __('message.msg_removed',[ 'name' => __('message.workout') ]);
                break;
            case 'categorydiet_image':
                $data = CategoryDiet::find($request->id);
                $message = __('message.msg_removed',[ 'name' => __('message.categorydiet') ]);
                break;
            case 'diet_image':
                $data = Diet::find($request->id);
                $message = __('message.msg_removed',[ 'name' => __('message.diet') ]);
                break;
             case 'category_image':
                $data = Category::find($request->id);
                $message = __('message.msg_removed',[ 'name' => __('message.category') ]);
                break;
             case 'level_image':
                $data = Level::find($request->id);
                $message = __('message.msg_removed',[ 'name' => __('message.level') ]);
                break;
            case 'bodypart_image':
                $data = BodyPart::find($request->id);
                $message = __('message.msg_removed',[ 'name' => __('message.bodypart') ]);
                break;
            case 'exercise_image':
                $data = Exercise::find($request->id);
                $message = __('message.msg_removed',[ 'name' => __('message.exercise') ]);
                break;
            case 'exercise_video':
                $data = Exercise::find($request->id);
                $message = __('message.msg_removed',[ 'name' => __('message.exercise') ]);
                break;
            case 'post_image':
                $data = Post::find($request->id);
                $message = __('message.msg_removed',[ 'name' => __('message.post') ]);
                break;
            case 'productcategory_image':
                $data = ProductCategory::find($request->id);
                $message = __('message.msg_removed',[ 'name' => __('message.productcategory') ]);
                break;
            case 'product_image':
                $data = Product::find($request->id);
                $message = __('message.msg_removed',[ 'name' => __('message.product') ]);
                break;
            default:
                $data = AppSetting::find($request->id);
                $message = __('message.msg_removed',[ 'name' => __('message.image') ]);
            break;

        }

        if($data != null){
            $data->clearMediaCollection($type);
        }

        $response = [
                'status' => true,
                'id' => $request->id,
                'image' => getSingleMedia($data,$type),
                'preview' => $type."_preview",
                'message' => $message
        ];
        return json_custom_response($response);
    }

    public function changeLanguage($locale)
    {
        App::setLocale($locale);
        session()->put('locale', $locale);
        return redirect()->back();
    }

    public function getAjaxList(Request $request)
    {
        $items = array();
        $value = $request->q;
        $goal_type_id = $request->goal_type_id;

        switch ($request->type) {
            case 'permission':
                $items = Permission::select('id','name as text')->whereNull('parent_id');
                if($value != ''){
                    $items->where('name', 'LIKE', $value.'%');
                }
                $items = $items->get();
                break;
        case 'categorydiet':
            $items = CategoryDiet::select('id','title as text')->where('status','active');
                if($value != ''){
                    $items->where('title', 'LIKE', '%'.$value.'%');
                }
                $items = $items->get();
                break;
        case 'level':
            $items = Level::select('id','title as text')->where('status','active');
                if($value != ''){
                    $items->where('title', 'LIKE', '%'.$value.'%');
                }
                $items = $items->get();
                break;
        case 'equipment':
            $items = Equipment::select('id','title as text')->where('status','active');
                if($value != ''){
                    $items->where('title', 'LIKE', '%'.$value.'%');
                }
                $items = $items->get();
                break;
        case 'workout_type':
            $items = WorkoutType::select('id','title as text')->where('status','active');
                if($value != ''){
                    $items->where('title', 'LIKE', '%'.$value.'%');
                }
                $items = $items->get();
                break;
        case 'bodypart':
            $items = BodyPart::select('id','title as text')->where('status','active');
                if($value != ''){
                    $items->where('title', 'LIKE', '%'.$value.'%');
                }
                $items = $items->get();
                break;
        case 'exercise':
            $items = Exercise::select('id','title as text')->where('status','active');
                if($value != ''){
                    $items->where('title', 'LIKE', '%'.$value.'%');
                }
                $items = $items->get();
                break;
        case 'tags':
                $items = Tags::select('id','title as text');
                if($value != ''){
                    $items->where('title', 'LIKE', '%'.$value.'%');
                }
                $items = $items->get();
                break;
        case 'category':
                $items = Category::select('id','title as text')->where('status','active');
                if($value != ''){
                    $items->where('title', 'LIKE', '%'.$value.'%');
                }
                $items = $items->get();
                break;
                case 'diet':
                    $items = Diet::select('id','title as text')->where('status','active');
                    if($value != ''){
                        $items->where('title', 'LIKE', '%'.$value.'%');
                    }
                    $items = $items->get();
                    break;
            case 'user':
                    $items = User::select('id','id as text')->where('status','active');
                    if($value != ''){
                        $items->where('id', 'LIKE', '%'.$value.'%');
                    }
                    $items = $items->get();
                    break;
        case 'productcategory':
                $items = ProductCategory::select('id','title as text');
                if($value != ''){
                    $items->where('title', 'LIKE', '%'.$value.'%');
                }
                $items = $items->get();
                break;
        case 'workout':
                $items = Workout::select('id','title as text');
                if($value != ''){
                    $items->where('title', 'LIKE', '%'.$value.'%');
                }
                $items = $items->get();
                break;
        case 'hours':
                $data = [];
                for ($x = 0; $x < 24; $x++) {

                    $val = $x < 10 ? '0'.$x : $x ;
                    $data[] = [
                        'id' => $val,
                        'text' => $val,
                    ];
                }
               $items = $data;
                break;

        case 'minute':
                    $data = [];
                    for ($x = 0; $x < 60; $x++) {
                        $val = $x < 10 ? '0'.$x : $x ;
                        $data[] = [
                            'id' => $val,
                            'text' => $val,
                        ];
                    }
                   $items = $data;
                    break;

        case 'second':
                        $data = [];
                        for ($x = 0; $x < 60; $x++) {
                            $val = $x < 10 ? '0'.$x : $x ;
                            $data[] = [
                                'id' => $val,
                                'text' => $val,
                            ];
                        }
                       $items = $data;
                        break;
        case 'package':
            $items = Package::select('id','name as text')->where('status','active');
                if($value != ''){
                    $items->where('name', 'LIKE', '%'.$value.'%');
                }
                $items = $items->get();
                break;
        case 'goal_type':
            $items = GoalType::select('id','title as text');
            if($value != ''){
                $items->where('title', 'LIKE', '%'.$value.'%');
            }
            $items = $items->get();
            break;
        case 'unit_type':
            $items = collect([]);

            // Only proceed if goal_type_id is provided and not empty
            if (!empty($goal_type_id)) {
                $items = UnitType::select('id', 'title as text');

                // Apply search filter if $value is provided
                if (!empty($value)) {
                    $items->where('title', 'LIKE', '%' . $value . '%');
                }

                // Assuming a relationship exists in the UnitType model to filter by goal type
                $items = $items->whereHas('goal_types', function ($query) use ($goal_type_id) {
                    $query->where('id', $goal_type_id);
                })->get();
            }
        break;
        default :
            break;
        }

        return response()->json(['status' => true, 'results' => $items]);
    }

    /*
     * Auth Routs
     */
    public function signin(Request $request)
    {
        return view('auth.login');
    }
    public function signup(Request $request)
    {
        return view('auth.register');
    }
    public function confirmmail(Request $request)
    {
        return view('auth.confirm-mail');
    }
    public function lockscreen(Request $request)
    {
        return view('auth.lockscreen');
    }
    public function recoverpw(Request $request)
    {
        return view('auth.forgot-password');
    }
    public function userprivacysetting(Request $request)
    {
        return view('auth.user-privacy-setting');
    }

    /*
     * Error Page Routs
     */

    public function error404(Request $request)
    {
        return view('errors.error404');
    }

    public function error500(Request $request)
    {
        return view('errors.error500');
    }
    public function maintenance(Request $request)
    {
        return view('errors.maintenance');
    }

    /*
     * Extra Page Routs
     */
    public function privacyPolicy()
    {
        $data = SettingData('privacy_policy', 'privacy_policy');
        return view('pages.privacy-policy', compact('data'));
    }

    public function termsCondition()
    {
        $data = SettingData('terms_condition', 'terms_condition');

        return view('pages.terms-condition', compact('data'));
    }

    public function home()
    {
        return view('pages.home');
    }
}
