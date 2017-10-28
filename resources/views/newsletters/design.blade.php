@extends('common.template')

@section('heading')
    Campaign Design
@stop

@section('content')

    {!! Form::model($campaign, array('method' => 'put', 'route' => array('campaigns.design.update', $campaign->id))) !!}

    @include('templates.partials.editor')

    <a href="{{ route('campaigns.template', $campaign->id) }}" class="btn btn-default">Back</a>
    {!! Form::submitButton('Save and continue') !!}
    {!! Form::close() !!}

@stop
