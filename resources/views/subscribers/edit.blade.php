@extends('common.template')

@section('title', "Edit Subscriber : {$subscriber->full_name}")

@section('heading')
    Edit Subscriber : {{ $subscriber->full_name }}
@stop

@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ route('subscribers.index') }}"><i class="fa fa-users"></i> Subscribers</a></li>
        <li class="active">Edit Subscriber : {{ $subscriber->full_name }}</li>
      </ol>
@endsection

@section('content')

    {!! Form::model($subscriber, ['method' => 'put', 'class' => 'form-horizontal', 'route' => ['subscribers.update', $subscriber->id]]) !!}

    @include('subscribers.partials.form')

    {!! Form::submitButton('Save') !!}

    {!! Form::close() !!}

@stop