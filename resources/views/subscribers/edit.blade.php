@extends('common.template')

@section('heading')
    Edit Subscriber : {{ $subscriber->name }}
@stop

@section('content')

    {!! Form::model($subscriber, ['method' => 'put', 'route' => ['subscribers.update', $subscriber->id]]) !!}

    @include('subscribers.partials.form')

@stop