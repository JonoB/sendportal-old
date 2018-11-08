@extends('common.template')

@section('heading')
    Create Automations
@stop

@section('content')

    {!! Form::open(array('route' => array('automations.store'))) !!}

    @include('automations.partials.form')

@stop
