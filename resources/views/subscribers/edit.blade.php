@extends('common.template')

@section('heading')
    Edit Subscriber§§ : {{ $subscriber->name }}
@stop

@section('content')

    {!! Form::model($subscriber, array('method' => 'put', 'route' => array('subscribers.update', $subscriber->id))) !!}

    @include('subscribers.partials.form')

@stop
