<x-app-layout :assets="$assets ?? []">
    <div>
        <?php $id = $id ?? null;?>
        @if(isset($id))
            {!! Form::model($data, [ 'route' => ['goal_progress.update', $id], 'method' => 'patch', 'enctype' => 'multipart/form-data' ]) !!}
        @else
{{--            {!! Form::open(['route' => ['goal_progress.store',$goal], 'method' => 'post', 'enctype' => 'multipart/form-data' ]) !!}--}}
            {!! Form::open(['route' => ['goal_progress.store', $goal->id], 'method' => 'post', 'enctype' => 'multipart/form-data']) !!}

        @endif
        {!! Form::hidden('user_id', auth()->id()) !!}

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">{{ $pageTitle }} for </h4>
                            <hr>
                            <h5 class="mt-2">{!! $goal->getDescription() !!}</h5>
                            <hr>
                        </div>
                        <div class="card-action">
                            <a href="{{ route('goal_progress.index', ['goal',$goal->id]) }} " class="btn btn-sm btn-primary" role="button">{{ __('message.back') }}</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-6">
                                {{ Form::label('progress_value', __('Progress').' <span class="text-danger">*</span>',[ 'class' => 'form-control-label' ], false ) }}
                                {{ Form::number('progress_value', old('progress_value'),[ 'placeholder' => __('Progress'),'class' =>'form-control','required']) }}
                            </div>

                            <div class="form-group col-md-6">
                                {{ Form::label('date', __('Date').' <span class="text-danger">*</span>',[ 'class' => 'form-control-label' ], false ) }}
{{--                                {{ Form::date('date', old('date'),[ 'placeholder' => __('Date'),'class' =>'form-control','required']) }}--}}
                                {{ Form::input('datetime-local', 'date', old('date'), ['placeholder' => __('Date'), 'class' => 'form-control', 'required']) }}

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
