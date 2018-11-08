@extends('common.template')

@section('title', 'Campaign Status')

@section('heading')
    Campaign Status
@stop

@section('content')

{{ $campaign->status->name }}

@stop
