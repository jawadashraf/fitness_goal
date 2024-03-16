<?php

namespace App\Http\Controllers;

use App\DataTables\GoalDataTable;
use Illuminate\Http\Request;
use App\DataTables\LevelDataTable;
use App\Models\Goal;
use App\Helpers\AuthHelper;

use App\Http\Requests\GoalRequest;

class GoalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(GoalDataTable $dataTable)
    {
        $pageTitle = __('message.list_form_title',['form' => __('message.goal')] );
        $auth_user = AuthHelper::authSession();
        if( !$auth_user->can('goal-list') ) {
            $message = __('message.permission_denied_for_account');
            return redirect()->back()->withErrors($message);
        }
        $assets = ['data-table'];

        $headerAction = $auth_user->can('goal-add') ? '<a href="'.route('goal.create').'" class="btn btn-sm btn-primary" role="button">'.__('message.add_form_title', [ 'form' => __('message.goal')]).'</a>' : '';

        return $dataTable->render('global.datatable', compact('pageTitle', 'auth_user', 'assets', 'headerAction'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if( !auth()->user()->can('goal-add') ) {
            $message = __('message.permission_denied_for_account');
            return redirect()->back()->withErrors($message);
        }
        $pageTitle = __('message.add_form_title',[ 'form' => __('message.goal')]);

        return view('goal.form', compact('pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GoalRequest $request)
    {
        if( !auth()->user()->can('goal-add') ) {
            $message = __('message.permission_denied_for_account');
            return redirect()->back()->withErrors($message);
        }

        $goal = Goal::create($request->all());

        return redirect()->route('goal.index')->withSuccess(__('message.save_form', ['form' => __('message.goal')]));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Goal::findOrFail($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if( !auth()->user()->can('goal-edit') ) {
            $message = __('message.permission_denied_for_account');
            return redirect()->back()->withErrors($message);
        }

        $data = Goal::findOrFail($id);
        $pageTitle = __('message.update_form_title',[ 'form' => __('message.goal') ]);

        return view('goal.form', compact('data','id','pageTitle'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(GoalRequest $request, $id)
    {
        if( !auth()->user()->can('goal-edit') ) {
            $message = __('message.permission_denied_for_account');
            return redirect()->back()->withErrors($message);
        }

        $goal = Goal::findOrFail($id);

        // goal data...
        $goal->fill($request->all())->update();

        if(auth()->check()){
            return redirect()->route('goal.index')->withSuccess(__('message.update_form',['form' => __('message.goal')]));
        }
        return redirect()->back()->withSuccess(__('message.update_form',['form' => __('message.goal') ] ));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if( !auth()->user()->can('goal-delete') ) {
            $message = __('message.permission_denied_for_account');
            return redirect()->back()->withErrors($message);
        }

        $goal = Goal::findOrFail($id);
        $status = 'errors';
        $message = __('message.not_found_entry', ['name' => __('message.goal')]);

        if($goal != '') {
            $goal->delete();
            $status = 'success';
            $message = __('message.delete_form', ['form' => __('message.goal')]);
        }

        if(request()->ajax()) {
            return response()->json(['status' => true, 'message' => $message ]);
        }

        return redirect()->back()->with($status,$message);
    }

    public function achievements()
    {
        $user = auth()->user();
        return view('achievements.index', ['achievements' => $user->goal_achievements, 'userName' => $user->first_name]);
    }
}
