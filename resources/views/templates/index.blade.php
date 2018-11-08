@extends('common.template')

@section('title', 'Email Templates')

@section('heading')
    Email Templates
@endsection

@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">Templates</li>
      </ol>
@endsection

@section('actions')
    <a class="btn btn-primary btn-sm btn-flat" href="{{ route('templates.create') }}">
        <i class="fa fa-plus"></i> New Template
    </a>
@endsection

@section('content')

    @include('templates.partials.grid')

@endsection
