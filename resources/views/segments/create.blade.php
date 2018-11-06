@extends('common.template')

@section('heading')
    New List
@stop

@section('content')

    {!! Form::open(['route' => ['segments.store']]) !!}

    @include('segments.partials.form')

@stop
