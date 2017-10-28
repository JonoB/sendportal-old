@extends('common.template')

@section('heading')
    Create Campaign
@stop

@section('content')

    {!! Form::open(array('route' => array('campaigns.store'))) !!}

    @include('campaigns.partials.form')

@stop
