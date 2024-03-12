@push('scripts')
    <script>

        (function($) {
            $(document).ready(function(){
                tinymceEditor('.tinymce-description',' ',function (ed) {

                }, 450)

                $(".select2tagsjs").select2({
                    width: "100%",
                    tags: true
                });

                $('#goal_type_id').change(function() {
                    var goalTypeId = $(this).val(); // Get the selected goal_type_id

                    var newUrl = '{{ route("ajax-list", ["type" => "unit_type"]) }}' + '&goal_type_id=' + goalTypeId; // Construct the new URL
                    console.log('goal type changed' + newUrl);
                    // Update the unit_type Select2 instance
                    $('#unit_type_id').select2({
                        width: '100%',
                        ajax: {
                            url: newUrl, // Use the new URL for AJAX requests
                            processResults: function (data) {
                                // Transform the data into the format expected by Select2.
                                console.log(data);
                                let items = [];
                                if (Array.isArray(data)) {
                                    items = data.map(item => ({ id: item.id, text: item.text }));
                                } else if (data.items && Array.isArray(data.items)) { // Adjust based on your actual data structure
                                    items = data.items.map(item => ({ id: item.id, text: item.text }));
                                }

                                // Return in format expected by Select2
                                return {
                                    results: items
                                };
                            }
                        },
                        placeholder: "{{ __('message.select_name', ['select' => __('message.unit_type')]) }}",
                    });

                    // Clear previous selections
                    $('#unit_type_id').val(null).trigger('change');
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
                                        'data-ajax--url' => route('ajax-list', ['type' => 'unit_type']),
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
                                {{ Form::label('status',__('message.status').' <span class="text-danger">*</span>',['class'=>'form-control-label'],false) }}
                                {{ Form::select('status',[ 'active' => __('message.active'), 'inactive' => __('message.inactive') ], old('status'), [ 'class' =>'form-control select2js','required']) }}
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-control-label" for="image">{{ __('message.image') }} </label>
                                <div class="">
                                    <input class="form-control file-input" type="file" name="level_image" accept="image/*">
                                </div>
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
