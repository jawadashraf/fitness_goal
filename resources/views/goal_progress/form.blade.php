@push('scripts')
    <script>

        (function($) {
            function updateUnitTypeSelect(goalTypeId) {
                // If Select2 was previously initialized, destroy it
                if ($('#unit_type_id').data('select2')) {
                    $('#unit_type_id').select2('destroy');
                }

                // Define the base URL for AJAX requests
                let ajaxUrl = '{{ route("ajax-list", ["type" => "unit_type"]) }}';
                if (goalTypeId) {
                    // Update the AJAX URL with the selected goal_type_id
                    ajaxUrl += '&goal_type_id=' + goalTypeId;
                }

                // Reinitialize Select2 with the new AJAX URL
                $('#unit_type_id').select2({
                    width: '100%',
                    ajax: {
                        url: ajaxUrl,
                        dataType: 'json',
                        processResults: function(data) {
                            console.log(data);
                            // Transform the data into the format expected by Select2
                            return {
                                results: data.results.map(item => ({ id: item.id, text: item.text }))
                            };
                        }
                    },
                    placeholder: 'Select a unit type',
                    allowClear: true,
                    minimumInputLength: 0,
                });
            }

            $(document).ready(function(){
                tinymceEditor('.tinymce-description',' ',function (ed) {

                }, 450)

                $(".select2tagsjs").select2({
                    width: "100%",
                    tags: true
                });

                updateUnitTypeSelect();

                $('#goal_type_id').change(function() {
                    const selectedGoalTypeId = $(this).val();
                    updateUnitTypeSelect(selectedGoalTypeId);
                });

            });
        })(jQuery);
    </script>
@endpush
<x-app-layout :assets="$assets ?? []">
    <div>
        <?php $id = $id ?? null;?>
        @if(isset($id))
            {!! Form::model($data, [ 'route' => ['goal.update', $id], 'method' => 'patch', 'enctype' => 'multipart/form-data' ]) !!}
        @else
            {!! Form::open(['route' => ['goal.store'], 'method' => 'post', 'enctype' => 'multipart/form-data' ]) !!}
        @endif
        {!! Form::hidden('user_id', auth()->id()) !!}

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">{{ $pageTitle }}</h4>
                        </div>
                        <div class="card-action">
                            <a href="{{ route('goal.index') }} " class="btn btn-sm btn-primary" role="button">{{ __('message.back') }}</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-6">
                                {{ Form::label('title', __('message.title').' <span class="text-danger">*</span>',[ 'class' => 'form-control-label' ], false ) }}
                                {{ Form::text('title', old('title'),[ 'placeholder' => __('message.title'),'class' =>'form-control','required']) }}
                            </div>

                            <div class="form-group col-md-4">
                                {{ Form::label('goal_type_id', __('message.goal_type').' <span class="text-danger">*</span>',[ 'class' => 'form-control-label' ], false) }}
                                {{ Form::select('goal_type_id', isset($id) ? [ optional($data->goal_type)->id => optional($data->goal_type)->title ] : [], old('goal_type_id'), [
                                        'class' => 'select2js form-group goal_type',
                                        'data-placeholder' => __('message.select_name',[ 'select' => __('message.goal_type') ]),
                                        'data-ajax--url' => route('ajax-list', ['type' => 'goal_type']),
                                        'required',
                                        'id' => 'goal_type_id',
                                    ])
                                }}
                            </div>

                            <div class="form-group col-md-4">
                                {{ Form::label('unit_type_id', __('message.unit_type').' <span class="text-danger">*</span>',[ 'class' => 'form-control-label' ], false) }}
                                {{ Form::select('unit_type_id', isset($id) ? [ optional($data->unit_type)->id => optional($data->unit_type)->title ] : [], old('unit_type_id'), [
                                        'class' => 'select2js form-group unit_type',
                                        'data-placeholder' => __('message.select_name',[ 'select' => __('message.unit_type') ]),
//                                        'data-ajax--url' => route('ajax-list', ['type' => 'unit_type', "goal_type_id"=>1]),
                                        'required',
                                        'id' => 'unit_type_id',
                                    ])
                                }}
                            </div>


                            <div class="form-group col-md-6">
                                {{ Form::label('target_value', __('Target').' <span class="text-danger">*</span>',[ 'class' => 'form-control-label' ], false ) }}
                                {{ Form::number('target_value', old('target_value'),[ 'placeholder' => __('Target'),'class' =>'form-control','required']) }}
                            </div>

                            <div class="form-group col-md-6">
                                {{ Form::label('start_date', __('Start Date').' <span class="text-danger">*</span>',[ 'class' => 'form-control-label' ], false ) }}
                                {{ Form::date('start_date', old('start_date'),[ 'placeholder' => __('Start Date'),'class' =>'form-control','required']) }}
                            </div>

                            <div class="form-group col-md-6">
                                {{ Form::label('end_date', __('End Date').' <span class="text-danger">*</span>',[ 'class' => 'form-control-label' ], false ) }}
                                {{ Form::date('end_date', old('end_date'),[ 'placeholder' => __('End Date'),'class' =>'form-control','required']) }}
                            </div>

                            <div class="form-group col-md-6">
                                {{ Form::label('status',__('message.status').' <span class="text-danger">*</span>',['class'=>'form-control-label'],false) }}
                                {{ Form::select('status',[ 'active' => __('message.active'), 'completed' => __('message.completed'), 'failed' => __('message.failed') ], old('status'), [ 'class' =>'form-control select2js','required']) }}
                            </div>

                        </div>
                        <hr>
                        {{ Form::submit( __('message.save'), ['class'=>'btn btn-md btn-primary float-end']) }}
                    </div>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</x-app-layout>
