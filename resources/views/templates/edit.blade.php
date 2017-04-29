@extends('common.template')

@section('heading')
    Edit Template : {{ $template->name }}
@stop

@section('content')

    {!! Form::model($template, array('method' => 'put', 'route' => array('templates.update', $template->id))) !!}

    @include('templates.partials.form')

    <div class="embed-responsive embed-responsive-16by9">
        <iframe class="embed-responsive-item"  src="{{ route('templates.iframe', $template->id) }}" frameborder="0"></iframe>
    </div>

@stop
