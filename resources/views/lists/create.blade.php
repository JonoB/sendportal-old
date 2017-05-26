@extends('common.template')

@section('heading')
    New List
@stop

@section('content')

    {!! Form::open(['route' => ['lists.store']]) !!}

    @include('lists.partials.form')

@stop
