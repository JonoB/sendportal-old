@extends('common.template')

@section('heading')
    New Subscriber
@stop

@section('content')

    {!! Form::open(['route' => ['subscribers.store'], 'class' => 'form-horizontal']) !!}

    @include('subscribers.partials.form')

    {!! Form::submitButton('Save') !!}

    {!! Form::close() !!}

@stop
