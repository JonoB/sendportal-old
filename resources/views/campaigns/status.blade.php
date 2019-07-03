@extends('layouts.app')

@section('title', 'Campaign Status')

@section('heading')
    Campaign Status
@stop

@section('content')

Your campaign is currently {{ $campaign->status->name }}

<div class="row text-center">
    <div class="col-sm-6">
        @include('svgs.undraw_in_progress')
    </div>
</div>

@stop
