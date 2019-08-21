@extends('layouts.app')

@section('title', 'Edit Automation Step')

@section('heading')
    Edit Automation Step
@stop

@section('content')

    <div class="card">
        <div class="card-header card-header-accent">
            <div class="card-header-inner">
                Edit Automation Step
            </div>
        </div>
        <div class="card-body">
            {!! Form::model($automationStep, ['method' => 'put', 'class' => 'form-horizontal', 'url' => route('automations.steps.update', [$automationStep->automation_id, $automationStep->id])]) !!}

            @include('automations.steps.partials.form')

            {!! Form::submitButton('Update') !!}
            {!! Form::close() !!}
        </div>
    </div>

@stop
