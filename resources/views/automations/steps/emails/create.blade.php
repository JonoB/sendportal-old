@extends('common.template')

@section('heading')
    Create Email
@stop

@section('content')

    {!! Form::open(['route' => ['automations.steps.email.store', $automationStep->automation->id, $automationStep->id], 'class' => 'form-horizontal']) !!}

    <p>Create an email for {{ $automationStep->name }}</p>

    @include('emails.partials.form')

    {!! Form::submitButton('Create') !!}
    {!! Form::close() !!}

@stop
