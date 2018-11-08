@extends('common.template')

@section('heading')
    Create Email
@stop

@section('content')

    {!! Form::open(['route' => ['automations.emails.store', $automation->id], 'class' => 'form-horizontal']) !!}

    <p>Create an automation email for {{ $automation->name }}</p>

    @include('emails.partials.form')

    {!! Form::submitButton('Create') !!}
    {!! Form::close() !!}

@stop
