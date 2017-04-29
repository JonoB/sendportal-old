@extends('common.template')

@section('heading')
    New Template
@stop

@section('content')

    {!! Form::open(array('route' => array('templates.store'))) !!}

    @include('templates.partials.form')

@stop
