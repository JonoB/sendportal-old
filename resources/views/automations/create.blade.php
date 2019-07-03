@extends('layouts.app')

@section('title', 'New Automation')

@section('heading')
    New Automation
@stop

@section('content')

    <div class="card">
        <div class="card-body">
            {!! Form::open(['route' => 'automations.store', 'class' => 'form-horizontal']) !!}

            @include('automations.partials.form')
        </div>
    </div>


@stop
