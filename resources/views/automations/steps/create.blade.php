@extends('layouts.app')

@section('title', 'Create Automation Step')

@section('heading')
    Create Automation Step
@stop

@section('content')

    <div class="card">
        <div class="card-header card-header-accent">
            <div class="card-header-inner">
                Create an automation step for {{ $automation->name }}
            </div>
        </div>
        <div class="card-body">
            {!! Form::open(['route' => ['automations.steps.store', $automation->id], 'class' => 'form-horizontal']) !!}

            @include('automations.steps.partials.form')

            {!! Form::submitButton('Create') !!}
            {!! Form::close() !!}
        </div>
    </div>

@stop
