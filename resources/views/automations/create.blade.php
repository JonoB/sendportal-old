@extends('layouts.app')

@section('title', 'New Automation')

@section('heading')
    New Automation
@stop

@section('content')

    {!! Form::open(['route' => 'automations.store', 'class' => 'form-horizontal']) !!}

    @include('automations.partials.form')

@stop
