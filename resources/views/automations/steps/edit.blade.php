@extends('common.template')

@section('heading')
    <div class="actions-bar">
        Edit Automation Step
    </div>
@stop

@section('content')

    <div class="row">
        <div class="col-sm-4">
            <div class="box box-primary">
                <div class="box-body automation-box-padding">
                    <h4>{{ $automationStep->name }}</h4>
                    <hr>
                    <ul>
                        <li>Sends {{ $automationStep->sends }}</li>
                        <li>Created on {{ $automationStep->created_at->toFormattedDateString() }}</li>
                        <li>Last updated on {{ $automationStep->updated_at->toFormattedDateString() }}</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-sm-8">
            <div class="box box-primary">
                <div class="box-body automation-box-padding">
                    <h4>Edit Configuration</h4>
                    <hr>
                    <div class="row">
                        <div class="col-12">
                            {!! Form::open(['route' => ['automations.steps.update', $automationStep->automation->id, $automationStep->id], 'method' => 'PUT', 'class' => 'form-horizontal']) !!}
                            @include('automations.steps.partials.form')

                            {!! Form::submitButton('Update') !!}
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
