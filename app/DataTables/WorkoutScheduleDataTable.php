<?php

namespace App\DataTables;

use App\Models\Workout;
use App\Models\WorkoutSchedule;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use App\Traits\DataTableTrait;

class WorkoutScheduleDataTable extends DataTable
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
            ->editColumn('workout.title', function($query) {
                return optional($query->workout)->title ?? '-';
            })
            ->filterColumn('workout.title', function($query, $keyword) {
                return $query->orWhereHas('workout', function($q) use($keyword) {
                    $q->where('title', 'like', "%{$keyword}%");
                });
            })
            ->editColumn('start', function($query){
                return dateAgoFormate($query->start, true);
            })
            ->editColumn('end', function($query){
                return dateAgoFormate($query->end, true);
            })
//            ->editColumn('updated_at', function($query){
//                return dateAgoFormate($query->updated_at, true);
//            })
            ->addColumn('action', function($workout_schedule){
                $id = $workout_schedule->id;
                return view('schedule.action',compact('workout_schedule','id'))->render();
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
    public function query(WorkoutSchedule $model)
    {
        $model = WorkoutSchedule::query()->with('workout.exercises');
        return $this->applyScopes($model);
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
            ['data' => 'workout.title', 'name' => 'workout.title', 'title' => __('message.title')],
            ['data' => 'start', 'name' => 'start', 'title' => __('Start')],
            ['data' => 'end', 'name' => 'end', 'title' => __('End')],
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
        return 'WorkoutSchedule_' . date('YmdHis');
    }
}
