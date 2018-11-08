@extends('common.template')

@section('title', 'New Template')

@section('heading')
    New Template
@stop

@section('content')

    {!! Form::open(['route' => ['templates.store'], 'class' => 'form-horizontal']) !!}

    @include('templates.partials.form')

@stop
