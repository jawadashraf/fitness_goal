<?php

namespace App\Http\Controllers;

use App\DataTables\GoalProgressDataTable;
use App\Helpers\AuthHelper;
use App\Models\Goal;
use App\Models\GoalProgress;
use Illuminate\Http\Request;

class GoalProgressController extends Controller
{
    public function index(Goal $goal)
    {
        $dataTable = new GoalProgressDataTable($goal);

        $pageTitle = __('message.list_form_title',['form' => __('message.goal_progress')] );
        $auth_user = AuthHelper::authSession();
        if( !$auth_user->can('goal-list') ) {
            $message = __('message.permission_denied_for_account');
            return redirect()->back()->withErrors($message);
        }
        $assets = ['data-table'];

        $parentDetail = $goal->getDescription(); //"<span class='text-success'>{$goal->title}</span>  | {$goal->goal_type->title} | {$goal->unit_type->title} | {$goal->target_value}";

        $headerAction = $auth_user->can('goal-add') ? '<a href="'.route('goal_progress.create', ['goal' => $goal->id]).'" class="btn btn-sm btn-primary" role="button">'.__('message.add_form_title', [ 'form' => __('message.goal_progress')]).'</a>' : '';

        return $dataTable->render('global.datatable', compact('pageTitle', 'auth_user', 'assets', 'headerAction', 'parentDetail'));

    }

    public function create(Goal $goal)
    {
        if( !auth()->user()->can('goal-add') ) {
            $message = __('message.permission_denied_for_account');
            return redirect()->back()->withErrors($message);
        }
        $pageTitle = __('message.add_form_title',[ 'form' => __('message.goal_progress')]);

        return view('goal_progress.form', compact('pageTitle','goal'));
    }

    public function store(Request $request, Goal $goal)
    {
        if( !auth()->user()->can('goal-add') ) {
            $message = __('message.permission_denied_for_account');
            return redirect()->back()->withErrors($message);
        }


        $data = $request->validate([
            // validation rules
            'date' => 'required|date',
            'progress_value' => 'required|numeric',
            // add other fields as necessary
        ]);

        $goal_progress = $goal->progress()->create($request->all());

        return redirect()->route('goal_progress.index', ['goal' => $goal->id])->withSuccess(__('message.save_form', ['form' => __('message.goal_progress')]));

    }

    public function edit($id)
    {
        if( !auth()->user()->can('goal-edit') ) {
            $message = __('message.permission_denied_for_account');
            return redirect()->back()->withErrors($message);
        }

        $data = GoalProgress::findOrFail($id);
        $goal = $data->goal;
        $pageTitle = __('message.update_form_title',[ 'form' => __('message.goal_progress') ]);

        return view('goal_progress.form', compact('data','id','pageTitle', 'goal'));

    }

    public function update(Request $request, $id)
    {
        if( !auth()->user()->can('goal-edit') ) {
            $message = __('message.permission_denied_for_account');
            return redirect()->back()->withErrors($message);
        }

        $goal_progress = GoalProgress::findOrFail($id);

        $data = $request->validate([
            // validation rules
            'date' => 'required|date',
            'progress_value' => 'required|numeric',
            // add other fields as necessary
        ]);
        // goal data...
        $goal_progress->fill($request->all())->update();

        if(auth()->check()){
            return redirect()->route('goal_progress.index', ['goal'=>$goal_progress->goal_id])
                ->withSuccess(__('message.update_form',['form' => __('message.goal_progress')]));
        }
        return redirect()->back()->withSuccess(__('message.update_form',['form' => __('message.goal_progress') ] ));

    }

    public function destroy($id)
    {
        if( !auth()->user()->can('goal-delete') ) {
            $message = __('message.permission_denied_for_account');
            return redirect()->back()->withErrors($message);
        }

        $goal_progress = GoalProgress::findOrFail($id);
        $status = 'errors';
        $message = __('message.not_found_entry', ['name' => __('message.goal')]);

        if($goal_progress != '') {
            $goal_progress->delete();
            $status = 'success';
            $message = __('message.delete_form', ['form' => __('message.goal_progress')]);
        }

        if(request()->ajax()) {
            return response()->json(['status' => true, 'message' => $message ]);
        }

        return redirect()->back()->with($status,$message);
    }
}
