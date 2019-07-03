@extends('layouts.app')

@section('title', 'Automations')

@section('heading')
    Automations
@endsection

@section('actions')
    <a class="btn btn-primary btn-sm btn-flat" href="{{ route('automations.create') }}">
        <i class="fa fa-plus"></i> New Automation
    </a>
@endsection

@section('content')

    <div class="card">
        <div class="card-header">
            <div class="card-header-inner">
                <h3>Coming soon!</h3>
            </div>
        </div>
    </div>
@endsection
