@extends('common.template')

@section('heading')
    New Automation
@stop

@section('content')

    {!! Form::open(['route' => 'automations.store', 'class' => 'form-horizontal']) !!}

    @include('automations.partials.form')

@stop
