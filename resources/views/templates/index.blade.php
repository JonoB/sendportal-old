@extends('common.template')

@section('heading')
    Email Templates
@endsection

@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">Templates</li>
      </ol>
@endsection

@section('content')
    <div class="actions-container">
        <a class="btn btn-primary btn-flat pull-right" href="{{ route('templates.create') }}">Create Template</a>
        <div class="clearfix"></div>
    </div>

    @include('templates.partials.grid')

@endsection
