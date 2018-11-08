@extends('common.template')

@section('heading')
    New Automation
@stop

@section('content')

    {!! Form::open(array('route' => array('automations.store'))) !!}

    @include('automations.partials.form')

@stop
