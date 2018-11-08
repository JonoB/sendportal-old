@extends('common.template')

@section('heading')
    Edit Email Content For Campaign {{ $email->mailable->name }}
@stop

@section('content')

    {!! Form::open(['route' => ['campaigns.emails.content.update', $email->mailable->id], 'method' => 'PUT', 'class' => 'form-horizontal']) !!}

    @include('emails.content.partials.form')

    {!! Form::submitButton('Update') !!}

    {!! Form::close() !!}

@stop
