@extends('common.template')

@section('heading')
    Edit List : {{ $subscriberList->name }}
@stop

@section('content')

    {!! Form::model($subscriberList, ['method' => 'put', 'route' => ['lists.update', $subscriberList->id]]) !!}

    @include('lists.partials.form')

@stop
