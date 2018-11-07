@extends('common.template')

@section('heading')
    Campaign Status
@stop

@section('content')

{{ $campaign->email->status->name }}

@stop
