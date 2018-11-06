@extends('common.template')

@section('heading')
    New Subscriber
@stop

@section('content')

    {!! Form::open(['route' => ['subscribers.store']]) !!}

    @include('subscribers.partials.form')

@stop
