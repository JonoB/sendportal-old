@extends('layouts.app')

@section('title', $automation->name)

@section('heading')
    {{ $automation->name }}
@stop

@section('actions')
    <a class="btn btn-primary btn-sm btn-flat"
        href="{{ route('automations.steps.create', ['automation' => $automation->id]) }}"><i class="fa fa-plus"></i>Create Automation Step
    </a>
@endsection

@section('content')

    <div class="card">
        <div class="card-table">
            <table class="table">
                <thead>
                <tr>
                    <th>Subject</th>
                    <th>Delay</th>
                    <th>Template</th>
                    <th>Content</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($automation->automation_steps as $step)
                    <tr>
                        <td>{{ $step->subject }}</td>
                        <td>{{ $step->delay_string }}</td>
                        <td>
                            @if( ! $step->template)
                                <span class="badge badge-danger">Not Set</span>
                            @else
                                {{ $step->template->name }}
                            @endif
                        </td>
                        <td>
                            @if( ! $step->content)
                                <span class="badge badge-danger">Not Set</span>
                            @else
                                <a href="">View</a>
                            @endif
                        </td>
                        <td><a href="{{ route('automations.steps.edit', [$automation->id, $step->id]) }}">Edit</a></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="100%">
                            <h5 class="text-center text-muted">You have not created any automation steps.</h5>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@stop
