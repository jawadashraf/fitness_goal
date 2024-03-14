<?php

namespace App\DataTables;

use App\Models\Goal;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

use App\Traits\DataTableTrait;

class GoalDataTable extends DataTable
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

            ->editColumn('status', function($query) {
                $status = 'warning';
                switch ($query->status) {
                    case 'ACTIVE':
                        $status = 'primary';
                        break;
                    case 'COMPLETED':
                        $status = 'success';
                        break;
                    case 'FAILED':
                        $status = 'danger';
                        break;
                }
                return '<span class="text-capitalize badge bg-'.$status.'">'.$query->status.'</span>';
            })
            ->editColumn('goal_type.title', function($query) {
                return optional($query->goal_type)->title ?? '-';
            })
            ->filterColumn('goal_type.title', function($query, $keyword) {
                return $query->orWhereHas('goal_type', function($q) use($keyword) {
                    $q->where('title', 'like', "%{$keyword}%");
                });
            })
            ->editColumn('unit_type.title', function($query) {
                return optional($query->unit_type)->title ?? '-';
            })
            ->filterColumn('unit_type.title', function($query, $keyword) {
                return $query->orWhereHas('unit_type', function($q) use($keyword) {
                    $q->where('title', 'like', "%{$keyword}%");
                });
            })
            ->editColumn('start_date', function ($query) {
                return dateAgoFormate($query->start_date, true);
            })
            ->editColumn('end_date', function ($query) {
                return dateAgoFormate($query->end_date, true);
            })
//            ->editColumn('created_at', function ($query) {
//                return dateAgoFormate($query->created_at, true);
//            })
//            ->editColumn('updated_at', function ($query) {
//                return dateAgoFormate($query->updated_at, true);
//            })
            ->addColumn('action', function($goal){
                $id = $goal->id;
                return view('goal.action',compact('goal','id'))->render();
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

    public function query(Goal $model)
    {
        $model = Goal::query()->with('goal_type','unit_type');
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
            ['data' => 'title', 'name' => 'title', 'title' => __('message.title')],
            ['data' => 'status', 'name' => 'status', 'title' => __('message.status')],
            ['data' => 'goal_type.title', 'name' => 'goal_type.title', 'title' => __('message.goal_type'), 'orderable' => false],
            ['data' => 'unit_type.title', 'name' => 'unit_type.title', 'title' => __('message.unit_type'), 'orderable' => false],
            ['data' => 'target_value', 'name' => 'target_value', 'title' => __('Target')],
            ['data' => 'start_date', 'name' => 'start_date', 'title' => __('Start Date')],
            ['data' => 'end_date', 'name' => 'end_date', 'title' => __('End Date')],
//            ['data' => 'created_at', 'name' => 'created_at', 'title' => __('message.created_at')],
//            ['data' => 'updated_at', 'name' => 'updated_at', 'title' => __('message.updated_at')],
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
        return 'Goal' . date('YmdHis');
    }
}
