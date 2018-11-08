@extends('common.template')

@section('heading')
    New Automation Step
@stop

@section('content')

    {!! Form::open(['route' => ['automations.steps.store', $automation->id], 'class' => 'form-horizontal']) !!}

    @include('automations.steps.partials.form')

@stop
