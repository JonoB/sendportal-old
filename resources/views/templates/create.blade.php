@extends('common.template')

@section('heading')
    New Template
@stop

@section('content')

    {!! Form::open(['route' => ['templates.store'], 'class' => 'form-horizontal']) !!}

    @include('templates.partials.form')

@stop
