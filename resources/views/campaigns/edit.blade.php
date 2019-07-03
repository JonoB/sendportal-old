@extends('layouts.app')

@section('title', 'Edit Campaign')

@section('heading')
    Edit Campaign
@stop

@section('content')

    {!! Form::open(['method' => 'put', 'class' => 'form-horizontal', 'route' => ['campaigns.update', $campaign->id]]) !!}


    @include('campaigns.partials.form')

@stop
