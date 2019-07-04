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
        <div class="card-table">
            <table class="table">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>From Name</th>
                    <th>From Email</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($automations as $automation)
                        <tr>
                            <td>
                                <a href="{{ route('automations.show', $automation->id) }}">{{ $automation->name }}</a>
                            </td>
                            <td>{{ $automation->from_name }}</td>
                            <td>{{ $automation->from_email }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
