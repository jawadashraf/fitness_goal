@push('scripts')
    <script>
        (function($) {
            $(document).ready(function()
            {
                var resetSequenceNumbers = function() {
                    $("#table_list tbody tr").each(function(i) {
                        $(this).find('td:first').text(i + 1);
                    });
                };
                // resetSequenceNumbers();
                $(".select2tagsjs").select2({
                    width: "100%",
                    tags: true
                });
                tinymceEditor('.tinymce-description',' ',function (ed) {

                }, 450)
                var row = 0;
                $('#add_button').on('click', function ()
                {
                    $(".select2tagsjs").select2("destroy");
                    var tableBody = $('#table_list').find("tbody");
                    var trLast = tableBody.find("tr:last");

                    trLast.find(".removebtn").show().fadeIn(300);

                    var trNew = trLast.clone();
                    row = trNew.attr('row');
                    row++;

                    trNew.attr('id','row_'+row).attr('data-id',0).attr('row',row);
                    trNew.find('[type="hidden"]').val(0).attr('data-id',0);

                    trNew.find('[id^="workout_days_id_"]').attr('name',"workout_days_id["+row+"]").attr('id',"workout_days_id_"+row).val('');
                    trNew.find('[id^="exercise_ids_"]').attr('name',"exercise_ids["+row+"][]").attr('id',"exercise_ids_"+row).val('');
                    trNew.find('[id^="is_rest_no_"]').attr('name',"is_rest["+row+"]").attr('id',"is_rest_no_"+row).val('0');
                    trNew.find('[id^="is_rest_yes_"]').attr('name',"is_rest["+row+"]").attr('id',"is_rest_yes_"+row).val('1').prop('checked', false);

                    trNew.find('[id^="remove_"]').attr('id',"remove_"+row).attr('row',row);

                    trLast.after(trNew);
                    $(".select2tagsjs").select2({
                        width: "100%",
                        tags: true
                    });
                    resetSequenceNumbers();
                });

                $(document).on('click','.removebtn', function()
                {
                    var row = $(this).attr('row');
                    var delete_row  = $('#row_'+row);
                    // console.log(delete_row);
                    var check_exists_id = delete_row.attr('data-id');
                    var total_row = $('#table_list tbody tr').length;
                    var user_response = confirm("{{ __('message.delete_msg') }}");
                    if(!user_response) {
                        return false;
                    }

                    if(total_row == 1){
                        $(document).find('#add_button').trigger('click');
                    }
                    // console.log(check_exists_id);
                    if(check_exists_id != 0 ) {
                        $.ajax({
                            url: "{{ route('workoutdays.exercise.delete')}}",
                            type: 'post',
                            data: {'id': check_exists_id, '_token': $('input[name=_token]').val()},
                            dataType: 'json',
                            success: function (response) {
                                if(response['status']) {
                                    delete_row.remove();
                                    showMessage(response.message);
                                } else {
                                    errorMessage(response.message);
                                }
                            }
                        });
                    } else {
                        delete_row.remove();
                    }

                    resetSequenceNumbers();
                })
            });
            function showMessage(message) {
                Swal.fire({
                    icon: 'success',
                    title: "{{ __('message.done') }}",
                    text: message,
                    confirmButtonColor: "var(--bs-primary)",
                    confirmButtonText: "{{ __('message.ok') }}"
                });
            }

            function errorMessage(message) {
                Swal.fire({
                    icon: 'error',
                    title: "{{ __('message.opps') }}",
                    text: message,
                    confirmButtonColor: "var(--bs-primary)",
                    confirmButtonText: "{{ __('message.ok') }}"
                });
            }
        })(jQuery);
    </script>
@endpush
<x-app-layout :assets="$assets ?? []">
    <div>
        <?php $id = $id ?? null;?>
        @if(isset($id))
            {!! Form::model($data, [ 'route' => [ 'schedule.update', $id], 'method' => 'patch', 'enctype' => 'multipart/form-data' ]) !!}
        @else
            {!! Form::open(['route' => ['schedule.store'], 'method' => 'post', 'enctype' => 'multipart/form-data' ]) !!}
        @endif

        {!! Form::hidden('user_id', auth()->id() ) !!}
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">{{ $pageTitle }}</h4>
                        </div>
                        <div class="card-action">
                            <a href="{{ route('schedule.index') }} " class="btn btn-sm btn-primary" role="button">{{ __('message.back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-4">
                                {{ Form::label('workout_id', __('message.workout').' <span class="text-danger">*</span>',[ 'class' => 'form-control-label' ], false) }}
                                @if(isset($id))
                                    <h6>{{ $data->workout->title }}</h6>
                                    @else

                                {{ Form::select('workout_id', isset($id) ? [ optional($data->workout)->id => optional($data->workout)->title ] : [], old('workout_id'), [
                                        'class' => 'select2js form-group workout',
                                        'data-placeholder' => __('message.select_name',[ 'select' => __('message.workout') ]),
                                        'data-ajax--url' => route('ajax-list', ['type' => 'workout']),
                                        'required',
                                        'id' => 'workout_id',
                                    ])
                                }}
                                @endif
                            </div>

                            <div class="form-group col-md-4">
                                {{ Form::label('start', __('Date').' <span class="text-danger">*</span>',[ 'class' => 'form-control-label' ], false ) }}
                                {{ Form::input('datetime-local', 'start', old('start'), ['placeholder' => __('Date'), 'class' => 'form-control', 'required']) }}

                            </div>

                            <div class="form-group col-md-4">
                                {{ Form::label('end', __('End').' <span class="text-danger">*</span>',[ 'class' => 'form-control-label' ], false ) }}
                                {{ Form::input('datetime-local', 'end', old('end'), ['placeholder' => __('End'), 'class' => 'form-control', 'required']) }}

                            </div>

                        </div>

                        <hr>
                        @if(isset($id))
                            <fieldset class="workout-schedule-progress">
                                <legend>Workout Schedule Progress</legend>
                                @foreach ($data->workout_schedule_progress as $schedule_progress)
                                    <div class="form-group">
                                        <label for="schedule_progress-{{ $schedule_progress->id }}">
                                            Progress for {{ $schedule_progress->exercise->title }}:
                                        </label>
                                        <input type="number" class="form-control" id="schedule_progress-{{ $schedule_progress->id }}"
                                               name="schedule_progress[{{ $schedule_progress->id }}]" value="{{ $schedule_progress->progress ?? 0 }}"
                                               min="0" step="1" aria-describedby="progressHelp-{{ $schedule_progress->id }}">
                                        <small id="progressHelp-{{ $schedule_progress->id }}" class="form-text text-muted">
                                            Enter the progress for {{ $schedule_progress->exercise->title }}.
                                        </small>
                                    </div>
                                @endforeach
                            </fieldset>
                                <hr>
                        @endif

                        {{ Form::submit( __('message.save'), ['class' => 'btn btn-md btn-primary float-end']) }}
                    </div>
                </div>
            </div>
        </div>


        {!! Form::close() !!}
    </div>

</x-app-layout>
