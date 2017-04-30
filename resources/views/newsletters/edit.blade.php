@extends('common.template')

@section('heading')
    Edit Template : {{ $template->name }}
@stop

@section('content')

    {!! Form::model($template, array('method' => 'put', 'route' => array('templates.update', $template->id))) !!}


    @include('templates.partials.form')

@stop
