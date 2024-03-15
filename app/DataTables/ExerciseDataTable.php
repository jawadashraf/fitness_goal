<?php

namespace App\DataTables;

use App\Models\Exercise;
use App\Models\Equipment;
use App\Models\Level;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

use App\Traits\DataTableTrait;

class ExerciseDataTable extends DataTable
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
            ->editColumn('equipment.title', function($query) {
                return optional($query->equipment)->title ?? '-';
            })
            ->filterColumn('equipment.title', function($query, $keyword) {
                return $query->orWhereHas('equipment', function($q) use($keyword) {
                    $q->where('title', 'like', "%{$keyword}%");
                });
            })

            ->editColumn('goal_type.title', function($query) {
                return optional($query->goal_type)->title ?? '-';
            })
            ->filterColumn('goal_type.title', function($query, $keyword) {
                return $query->orWhereHas('goal_type', function($q) use($keyword) {
                    $q->where('title', 'like', "%{$keyword}%");
                });
            })
            ->editColumn('created_at', function ($query) {
                return dateAgoFormate($query->created_at, true);
            })
            ->editColumn('updated_at', function ($query) {
                return dateAgoFormate($query->updated_at, true);
            })
            ->addColumn('action', function($exercise){
                $id = $exercise->id;
                return view('exercise.action',compact('exercise','id'))->render();
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
     * @param \App\Models\Exercise $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Exercise $model)
    {
        $model = Exercise::query()->with('equipment','level', 'goal_type');
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
            ['data' => 'title', 'name' => 'title', 'title' => __('message.title')],
            ['data' => 'equipment.title', 'name' => 'equipment.title', 'title' => __('message.equipment'), 'orderable' => false],
            ['data' => 'goal_type.title', 'name' => 'goal_type.title', 'title' => __('message.goal_type'), 'orderable' => false],
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
        return 'Exercise' . date('YmdHis');
    }
}
