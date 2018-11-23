@extends('common.template')

@section('title', 'Email Templates')

@section('heading')
    Email Templates
@endsection

@section('actions')
    <a class="btn btn-primary btn-sm btn-flat" href="{{ route('templates.create') }}">
        <i class="fa fa-plus"></i> New Template
    </a>
@endsection

@section('content')

    @include('templates.partials.grid')

@endsection
