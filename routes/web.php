<?php

// Controllers
use App\Http\Controllers\GoalController;
use App\Http\Controllers\GoalProgressController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\RewardController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\Security\RolePermission;
use App\Http\Controllers\Security\RoleController;
use App\Http\Controllers\Security\PermissionController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\LanguageController;

use App\Http\Controllers\UserController;
use App\Mail\WelcomeEmail;
use Illuminate\Support\Facades\Artisan;
// Packages
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\CategoryDietController;
use App\Http\Controllers\WorkoutTypeController;
use App\Http\Controllers\DietController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TagsController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\BodyPartController;
use App\Http\Controllers\WorkoutController;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PackageController;

use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductController;

use App\Http\Controllers\PushNotificationController;

use App\Http\Controllers\SubscriptionController;

use App\Http\Controllers\QuotesController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

require __DIR__.'/auth.php';

Route::get('/storage', function () {
    Artisan::call('storage:link');
});

Route::get('optimize', function () {
    Artisan::call('optimize:clear');
});


Route::get('language/{locale}', [ HomeController::class, 'changeLanguage'])->name('change.language');

Route::group(['middleware' => [ 'auth', 'useractive' ]], function () {

    // Permission Module
    Route::resource('permission', PermissionController::class);
    Route::get('permission/add/{type}',[ PermissionController::class, 'addPermission' ])->name('permission.add');
    Route::post('permission/save',[ PermissionController::class, 'savePermission' ])->name('permission.save');

	Route::resource('role', RoleController::class);

    // Dashboard Routes
//    Route::get('/', [HomeController::class, 'index']);
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
	Route::get('changeStatus', [ HomeController::class, 'changeStatus'])->name('changeStatus');

    // Users Module
    Route::resource('users', UserController::class);
    Route::resource('equipment', EquipmentController::class);

    //Fitness Workout
    Route::resource('workouttype', WorkoutTypeController::class);

    Route::resource('category', CategoryController::class);

    //FitnessTags
    Route::resource('tags', TagsController::class);
    //Fitnessleval
    Route::resource('level', LevelController::class);
    Route::resource('goal', GoalController::class);

    Route::get('/goal/{goal}/goal_progress',[GoalProgressController::class, 'index'])->name('goal_progress.index');
    Route::get('/goal/{goal}/goal_progress/create', [GoalProgressController::class, 'create'])->name('goal_progress.create');
    Route::post('/goal/{goal}/goal_progress/store', [GoalProgressController::class, 'store'])->name('goal_progress.store');
//    Route::get('/goal/{goal}/goal_progress/{goalProgress}/edit', [GoalProgressController::class, 'edit'])->name('goal_progress.edit');
    Route::get('/goal_progress/{id}/edit', [GoalProgressController::class, 'edit'])->name('goal_progress.edit');
    Route::patch('/goal/{goal}/goal_progress/update', [GoalProgressController::class, 'update'])->name('goal_progress.update');
    Route::delete('/goal_progress/{id}', [GoalProgressController::class, 'destroy'])->name('goal_progress.destroy');


    Route::resource('bodypart', BodyPartController::class);

    Route::resource('exercise', ExerciseController::class);

    Route::resource('workout', WorkoutController::class);

    Route::post('workoutdays-exercise-delete', [ WorkoutController::class , 'workoutDaysExerciseDelete'])->name('workoutdays.exercise.delete');


    Route::post('remove-file',[ HomeController::class, 'removeFile' ])->name('remove.file');

    Route::get('setting/{page?}', [ SettingController::class, 'settings'])->name('setting.index');
    Route::post('layout-page', [ SettingController::class, 'layoutPage'])->name('layout_page');
    Route::post('settings/save', [ SettingController::class , 'settingsUpdates'])->name('settingsUpdates');
	Route::post('env-setting', [ SettingController::class , 'envChanges'])->name('envSetting');

    Route::post('update-profile', [ SettingController::class , 'updateProfile'])->name('updateProfile');
    Route::post('change-password', [ SettingController::class , 'changePassword'])->name('changePassword');

    Route::get('pages/term-condition',[ SettingController::class, 'termAndCondition'])->name('pages.term_condition');
    Route::post('term-condition-save',[ SettingController::class, 'saveTermAndCondition'])->name('pages.term_condition_save');

    Route::get('pages/privacy-policy',[ SettingController::class, 'privacyPolicy'])->name('pages.privacy_policy');
    Route::post('privacy-policy-save',[ SettingController::class, 'savePrivacyPolicy'])->name('pages.privacy_policy_save');

    Route::resource('pushnotification', PushNotificationController::class);
// Add this to web.php or api.php
    Route::post('/save-player-id', [UserController::class, 'savePlayerId']);

    Route::resource('quotes', QuotesController::class);

    Route::get('/notifications', [NotificationController::class, 'getUserNotifications'])->name('notifications');
    Route::get('/notifications/unread_count', [NotificationController::class, 'getUnreadNotificationsCount'])->name('notifications.unread_count');

    Route::get('/rewards', [RewardController::class, 'index'])->name('rewards');
    Route::get('/achievements', [GoalController::class, 'achievements'])->name('achievements');
// Schedule Routes
    Route::get('/schedule', [ScheduleController::class, 'index'])->name('schedule.index');
    Route::get('/schedule/calendar', [ScheduleController::class, 'calendar'])->name('schedule.calendar');
    Route::get('/schedule/create', [ScheduleController::class, 'create'])->name('schedule.create');
    Route::post('/schedule/store', [ScheduleController::class, 'store'])->name('schedule.store');
//    Route::get('fullcalender', [ScheduleController::class, 'index']);
    Route::get('/events', [ScheduleController::class, 'getEvents']);
    Route::get('/schedule/delete/{id}', [ScheduleController::class, 'deleteEvent']);
    Route::delete('/schedule/destroy/{id}', [ScheduleController::class, 'destroy'])->name('schedule.destroy');
    Route::get('/schedule/{id}/edit', [ScheduleController::class, 'edit'])->name('schedule.edit');
    Route::patch('/schedule/{id}/update', [ScheduleController::class, 'update'])->name('schedule.update');
    Route::post('/schedule/{id}/update_event_on_drop', [ScheduleController::class, 'update_event_on_drop']);
    Route::post('/schedule/{id}/resize', [ScheduleController::class, 'resize']);

    Route::post('/schedule/create-from-drop', [ScheduleController::class, 'createFromDrop']);
});

Route::get('/ajax-list',[ HomeController::class, 'getAjaxList' ])->name('ajax-list');


//Auth pages Routs
Route::group(['prefix' => 'auth'], function() {
    Route::get('signin', [HomeController::class, 'signin'])->name('auth.signin');
    Route::get('signup', [HomeController::class, 'signup'])->name('auth.signup');
    Route::get('confirmmail', [HomeController::class, 'confirmmail'])->name('auth.confirmmail');
    Route::get('lockscreen', [HomeController::class, 'lockscreen'])->name('auth.lockscreen');
    Route::get('recover-password', [HomeController::class, 'recoverpw'])->name('auth.recover-password');
    Route::get('userprivacysetting', [HomeController::class, 'userprivacysetting'])->name('auth.userprivacysetting');
});

//Error Page Route
Route::group(['prefix' => 'errors'], function() {
    Route::get('error404', [HomeController::class, 'error404'])->name('errors.error404');
    Route::get('error500', [HomeController::class, 'error500'])->name('errors.error500');
    Route::get('maintenance', [HomeController::class, 'maintenance'])->name('errors.maintenance');
});



//Extra Page Routs
Route::get('privacy-policy',[ HomeController::class, 'privacyPolicy' ])->name('privacyPolicy');
Route::get('terms-condition',[ HomeController::class, 'termsCondition' ])->name('termsCondition');
Route::get('/',[ HomeController::class, 'home' ])->name('home');
