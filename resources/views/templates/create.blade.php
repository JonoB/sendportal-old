@extends('common.template')

@section('title', 'New Template')

@section('heading')
    New Template
@stop

@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ route('templates.index') }}"><i class="fa fa-file-text"></i> Templates</a></li>
        <li class="active">New Template</li>
      </ol>
@endsection

@section('content')

    {!! Form::open(['route' => ['templates.store'], 'class' => 'form-horizontal']) !!}

    @include('templates.partials.form')

@stop
