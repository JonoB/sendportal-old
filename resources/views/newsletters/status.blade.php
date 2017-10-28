@extends('common.template')

@section('heading')
    Campaign Status
@stop

@section('content')

{{ $campaign->status->name }}

@stop
