@extends('common.template')

@section('heading')
    New List
@stop

@section('content')

    {!! Form::open(['route' => ['segments.store'], 'class' => 'form-horizontal']) !!}

    @include('segments.partials.form')

@stop
