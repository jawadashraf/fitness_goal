<?php

namespace App\DataTables;

use App\Models\Goal;
use App\Models\GoalProgress;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

use App\Traits\DataTableTrait;

class GoalProgressDataTable extends DataTable
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
        $temp =  datatables()
            ->eloquent($query)
//            ->editColumn('goal.goal_type.title', function($query) {
//                return optional($query->goal->goal_type)->title ?? '-';
//            })
//            ->filterColumn('goal.goal_type.title', function($query, $keyword) {
//                return $query->orWhereHas('goal.goal_type', function($q) use($keyword) {
//                    $q->where('title', 'like', "%{$keyword}%");
//                });
//            })
//            ->editColumn('unit_type.title', function($query) {
//                return optional($query->unit_type)->title ?? '-';
//            })
//            ->filterColumn('unit_type.title', function($query, $keyword) {
//                return $query->orWhereHas('unit_type', function($q) use($keyword) {
//                    $q->where('title', 'like', "%{$keyword}%");
//                });
//            })
            ->editColumn('date', function ($query) {
                return dateAgoFormate($query->date, true);
            })
            ->editColumn('created_at', function ($query) {
                return dateAgoFormate($query->created_at, true);
            })
            ->editColumn('updated_at', function ($query) {
                return dateAgoFormate($query->updated_at, true);
            })
            ->addColumn('action', function($goal_progress){
                $id = $goal_progress->id;
                return view('goal_progress.action',compact('goal_progress','id'))->render();
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

        return $temp;
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Goal $model
     * @return \Illuminate\Database\Eloquent\Builder
     */

    public function query(GoalProgress $model)
    {
        $model = GoalProgress::query()->with('goal.goal_type', 'goal.unit_type');
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
                ->title(__('S#'))
                ->orderable(false),
            ['data' => 'progress_value', 'name' => 'progress_value', 'title' => __('Progress')],
            ['data' => 'date', 'name' => 'date', 'title' => __('Recording Date')],
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
        return 'GoalProgress' . date('YmdHis');
    }
}
