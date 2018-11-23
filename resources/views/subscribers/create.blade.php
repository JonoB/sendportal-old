@extends('common.template')

@section('title', 'New Subscriber')

@section('heading')
    New Subscriber
@stop

@section('content')

    {!! Form::open(['route' => ['subscribers.store'], 'class' => 'form-horizontal']) !!}

    @include('subscribers.partials.form')

    {!! Form::submitButton('Save') !!}

    {!! Form::close() !!}

@stop
