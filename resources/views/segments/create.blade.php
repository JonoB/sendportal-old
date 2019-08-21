@extends('layouts.app')

@section('title', 'New Segment')

@section('heading')
    New Segment
@stop

@section('content')

    {!! Form::open(['route' => ['segments.store'], 'class' => 'form-horizontal']) !!}

    @include('segments.partials.form')

    {!! Form::submitButton('Save') !!}

    {!! Form::close() !!}
@stop
