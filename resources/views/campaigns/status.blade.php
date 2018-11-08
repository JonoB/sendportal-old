@extends('common.template')

@section('title', 'Campaign Status')

@section('heading')
    Campaign Status
@stop

@section('content')

{{ $campaign->email->status->name }}

@stop
