@extends('common.template')

@section('title', "Edit Subscriber : {$subscriber->full_name}")

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.min.css">
@endsection

@section('heading')
    Edit Subscriber : {{ $subscriber->full_name }}
@stop

@section('content')

    {!! Form::model($subscriber, ['method' => 'put', 'class' => 'form-horizontal', 'route' => ['subscribers.update', $subscriber->id]]) !!}

    @include('subscribers.partials.form')

    <div class="form-group form-group-subscribers">
        <label for="id-field-subscribers" class="control-label col-sm-2">Segments</label>
        <div class="col-sm-10">
            <select name="segments[]" id="id-field-subscribers" multiple="multiple">
                @foreach($segments as $segment)
                    <option value="{{ $segment->id }}">{{ $segment->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {!! Form::submitButton('Save') !!}

    {!! Form::close() !!}

@stop

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js"></script>

    <script>
        $('select[name="segments[]"]').selectize({
            plugins: ['remove_button'],
            items: {!! $subscriber->segments->pluck('id')->toJson() !!}
        });
    </script>
@stop