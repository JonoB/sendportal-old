@extends('common.template')

@section('title', "Edit Subscriber : {$subscriber->full_name}")

@section('heading')
    Edit Subscriber : {{ $subscriber->full_name }}
@stop

@section('content')

    {!! Form::model($subscriber, ['method' => 'put', 'class' => 'form-horizontal', 'route' => ['subscribers.update', $subscriber->id]]) !!}

    @include('subscribers.partials.form')

    {!! Form::submitButton('Save') !!}

    {!! Form::close() !!}

@stop