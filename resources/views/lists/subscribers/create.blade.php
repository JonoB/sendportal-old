@extends('common.template')

@section('heading')
    New Subscriber In {{ $list->name }}
@stop

@section('content')

    {!! Form::open(array('route' => array('lists.subscribers.store', $list->id))) !!}

    @include('lists.subscribers.partials.form')

    {!! Form::close() !!}
@stop
