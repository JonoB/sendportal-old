@extends('common.template')

@section('heading')
    Edit Email Content For Automation {{ $email->mailable->name }}
@stop

@section('content')

    {!! Form::open(['route' => ['automations.steps.email.content.update', $email->mailable->automation->id, $email->mailable->id], 'method' => 'PUT', 'class' => 'form-horizontal']) !!}

    @include('emails.content.partials.form')

    {!! Form::submitButton('Update') !!}

    {!! Form::close() !!}

@stop
