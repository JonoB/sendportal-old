@extends('common.template')

@section('title', "Edit Segment : {$segment->name}")

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.min.css">
@stop

@section('heading')
    Edit Segment : {{ $segment->name }}
@stop

@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ route('segments.index') }}"><i class="fa fa-list"></i> Segments</a></li>
        <li class="active">Edit Segment : {{ $segment->name }}</li>
      </ol>
@endsection

@section('content')

    {!! Form::model($segment, ['method' => 'put', 'class' => 'form-horizontal', 'route' => ['segments.update', $segment->id]]) !!}

    @include('segments.partials.form')

    {!! Form::submitButton('Save') !!}

    {!! Form::close() !!}
@stop

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js"></script>

    <script>
        $('select[name="subscribers[]"]').selectize({
            items: {!! $segment->subscribers->pluck('id')->toJson() !!},
            render: {
                item: function (value, escape) {
                    var out = '';

                    if (value.disabled) {
                        out += '<del>' + escape(value.text) + '</del>';
                    } else {
                        out += escape(value.text);
                    }

                    out = '<div class="item" data-value="' + escape(value) + '">' + out + '</div>';

                    return out;
                }
            }
        });
    </script>
@stop