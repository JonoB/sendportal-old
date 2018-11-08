@extends('common.template')

@section('title', 'New Subscriber')

@section('heading')
    New Subscriber
@stop

@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ route('subscribers.index') }}"><i class="fa fa-users"></i> Subscribers</a></li>
        <li class="active">New Subscriber</li>
      </ol>
@endsection

@section('content')

    {!! Form::open(['route' => ['subscribers.store'], 'class' => 'form-horizontal']) !!}

    @include('subscribers.partials.form')

    {!! Form::submitButton('Save') !!}

    {!! Form::close() !!}

@stop
