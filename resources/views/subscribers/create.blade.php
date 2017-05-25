@extends('common.template')

@section('heading')
    New Subscriber
@stop

@section('content')

    {!! Form::open(array('route' => array('subscribers.store'))) !!}

    @include('subscribers.partials.form')

@stop
