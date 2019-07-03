@extends('common.template')

@section('title', 'Campaign Design')

@section('heading')
    Campaign Design
@stop

@section('content')

    {!! Form::model($campaign, array('method' => 'put', 'route' => array('campaigns.content.update', $campaign->id))) !!}

    @include('templates.partials.editor')

    <br>

    <a href="{{ route('campaigns.template', $campaign->id) }}" class="btn btn-link"><i class="fa fa-arrow-left"></i> Back</a>

    <button class="btn btn-primary" type="submit">Save and continue</button>

    {!! Form::close() !!}
@stop
