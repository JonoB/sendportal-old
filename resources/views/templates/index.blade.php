@extends('common.template')

@section('heading')
    Email Templates
@endsection

@section('content')
    <div class="actions-container">
        <a class="btn btn-primary btn-flat pull-right" href="{{ route('templates.create') }}">Create Template</a>
        <div class="clearfix"></div>
    </div>

    @include('templates.partials.grid')

@endsection
