<?php

namespace App\DataTables;

use App\Models\Workout;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use App\Traits\DataTableTrait;

class WorkoutDataTable extends DataTable
{
    use DataTableTrait;
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)

            ->editColumn('status', function($query) {
                $status = 'warning';
                switch ($query->status) {
                    case 'active':
                        $status = 'primary';
                        break;
                    case 'inactive':
                        $status = 'warning';
                        break;
                }
                return '<span class="text-capitalize badge bg-'.$status.'">'.$query->status.'</span>';
            })
            ->editColumn('level.title', function($query) {
                return optional($query->level)->title ?? '-';
            })
            ->filterColumn('level.title', function($query, $keyword) {
                return $query->orWhereHas('level', function($q) use($keyword) {
                    $q->where('title', 'like', "%{$keyword}%");
                });
            })
            ->editColumn('workout_type.title', function($query) {
                return optional($query->workouttype)->title ?? '-';
            })
            ->filterColumn('workout_type.title', function($query, $keyword) {
                return $query->orWhereHas('workouttype', function($q) use($keyword) {
                    $q->where('title', 'like', "%{$keyword}%");
                });
            })
            ->editColumn('created_at', function($query){
                return dateAgoFormate($query->created_at, true);
            })
            ->editColumn('updated_at', function($query){
                return dateAgoFormate($query->updated_at, true);
            })
            ->addColumn('action', function($workout){
                $id = $workout->id;
                return view('workout.action',compact('workout','id'))->render();
            })
            ->addIndexColumn()
            ->order(function ($query) {
                if (request()->has('order')) {
                    $order = request()->order[0];
                    $column_index = $order['column'];

                    $column_name = 'id';
                    $direction = 'desc';
                    if( $column_index != 0) {
                        $column_name = request()->columns[$column_index]['data'];
                        $direction = $order['dir'];
                    }

                    $query->orderBy($column_name, $direction);
                }
            })
            ->rawColumns(['action','status']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Workout $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Workout $model)
    {
        $userId = auth()->id(); // Get the authenticated user's ID

        // Query for workouts where user_id is null (global workouts) or matches the authenticated user's ID
        return $model->newQuery()->where(function ($query) use ($userId) {
            $query->where('user_id', $userId)
                ->orWhereNull('user_id');
        });
    }



    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::make('DT_RowIndex')
                ->searchable(false)
                ->title(__('message.srno'))
                ->orderable(false),
            ['data' => 'title', 'name' => 'title', 'title' => __('message.title')],
            ['data' => 'level.title', 'name' => 'level.title', 'title' => __('message.level'), 'orderable' => false],
            ['data' => 'workout_type.title', 'name' => 'workout_type.title', 'title' => __('message.workouttype'), 'orderable' => false],
            ['data' => 'status', 'name' => 'status', 'title' => __('message.status')],
            ['data' => 'created_at', 'name' => 'created_at', 'title' => __('message.created_at')],
            ['data' => 'updated_at', 'name' => 'updated_at', 'title' => __('message.updated_at')],
            Column::computed('action')
                  ->exportable(false)
                  ->printable(false)
                  ->title(__('message.action'))
                  ->width(60)
                  ->addClass('text-center hide-search'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Workout_' . date('YmdHis');
    }
}
