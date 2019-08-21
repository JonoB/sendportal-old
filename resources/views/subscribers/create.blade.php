@extends('layouts.app')

@section('title', 'New Subscriber')

@section('heading')
    New Subscriber
@stop

@section('content')

    <div class="card">
        <div class="card-header card-header-accent">
            <div class="card-header-inner">
                Create Subscriber
            </div>
        </div>
        <div class="card-body">
            {!! Form::open(['route' => ['subscribers.store'], 'class' => 'form-horizontal']) !!}

            @include('subscribers.partials.form')

            {!! Form::submitButton('Save') !!}

            {!! Form::close() !!}
        </div>
    </div>

@stop
