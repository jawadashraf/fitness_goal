<?php

namespace App\Http\Controllers;

use App\DataTables\GoalProgressDataTable;
use App\Helpers\AuthHelper;
use App\Models\Goal;
use App\Models\GoalProgress;
use Illuminate\Http\Request;

class GoalProgressController extends Controller
{
    public function index(Goal $goal, GoalProgressDataTable $dataTable)
    {
        $pageTitle = __('message.list_form_title',['form' => __('message.goal_progress')] );
        $auth_user = AuthHelper::authSession();
        if( !$auth_user->can('goal-list') ) {
            $message = __('message.permission_denied_for_account');
            return redirect()->back()->withErrors($message);
        }
        $assets = ['data-table'];

        $parentDetail = "Goal 1 | Goal Type 1 | Goal Unit";

        $headerAction = $auth_user->can('goal-add') ? '<a href="'.route('goal_progress.create', ['goal' => $goal->id]).'" class="btn btn-sm btn-primary" role="button">'.__('message.add_form_title', [ 'form' => __('message.goal_progress')]).'</a>' : '';

        return $dataTable->render('global.datatable', compact('pageTitle', 'auth_user', 'assets', 'headerAction', 'parentDetail'));

    }

    public function create(Goal $goal)
    {
        return view('goal_progress.form', compact('goal'));
    }

    public function store(Request $request, Goal $goal)
    {
        $data = $request->validate([
            // validation rules
            'date' => 'required|date',
            'progress' => 'required|numeric',
            // add other fields as necessary
        ]);

        $goal->progress()->create($data);
        return redirect()->route('goal_progress.index', $goal->id);
    }

    public function edit(Goal $goal, GoalProgress $goalProgress)
    {
        return view('goal_progress.form', compact('goal', 'goalProgress'));
    }
}
