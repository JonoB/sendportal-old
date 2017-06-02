@extends('common.template')

@section('heading')
    Edit Subscriber : {{ $subscriber->full_name }} ({{ $subscriber->email }})
@stop

@section('content')

    {!! Form::model($subscriber, array('method' => 'put', 'route' => array('lists.subscribers.update', $listId, $subscriber->id))) !!}

    @include('lists.subscribers.partials.form')

    {!! Form::close() !!}

@stop
