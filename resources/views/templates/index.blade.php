@extends('common.template')

@section('heading')
    Email Templates
@endsection

@section('actions')
    <a class="btn btn-primary btn-sm btn-flat" href="{{ route('templates.create') }}">
        <i class="fa fa-plus"></i> Create Template
    </a>
@endsection

@section('content')

    @include('templates.partials.grid')

@endsection
