@extends('common.template')

@section('heading')
    New Email
@stop

@section('content')

    {!! Form::open(['route' => ['emails.store']]) !!}

    @include('emails.partials.form')

@stop
