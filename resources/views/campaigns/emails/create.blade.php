@extends('layouts.app')

@section('title', 'Create Email')

@section('heading')
    Create Email
@stop

@section('content')

    {!! Form::open(['route' => ['steps', $campaign->id], 'class' => 'form-horizontal']) !!}

    @include('emails.partials.form')

    {!! Form::submitButton('Create') !!}
    {!! Form::close() !!}

@stop
