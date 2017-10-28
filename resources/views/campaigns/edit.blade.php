@extends('common.template')

@section('heading')
    Edit Campaign
@stop

@section('content')

    {!! Form::model($campaign, array('method' => 'put', 'route' => array('campaigns.update', $campaign->id))) !!}


    @include('campaigns.partials.form')

@stop
