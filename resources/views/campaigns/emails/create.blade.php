@extends('common.template')

@section('title', 'Create Email')

@section('heading')
    Create Email
@stop

@section('content')

    {!! Form::open(['route' => ['campaigns.emails.store', $campaign->id], 'class' => 'form-horizontal']) !!}

    @include('emails.partials.form')

    {!! Form::submitButton('Create') !!}
    {!! Form::close() !!}

@stop
