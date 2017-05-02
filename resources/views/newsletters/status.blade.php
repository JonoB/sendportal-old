@extends('common.template')

@section('heading')
    Newsletter Status
@stop

@section('content')

{{ $newsletter->status->name }}

@stop
