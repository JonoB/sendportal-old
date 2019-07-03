@extends('layouts.app')

@section('title', $automation->name)

@section('heading')
    {{ $automation->name }}
@stop

@section('actions')
    <a class="btn btn-primary btn-sm btn-flat"
        href="{{ route('automations.emails.create', ['automation' => $automation->id]) }}"><i class="fa fa-plus"></i>Create Automation Step
    </a>
@endsection

@section('content')

    <div class="box box-primary">
        <div class="box-body no-padding">
            <table class="table table-bordered table-responsive">
                <thead>
                <tr>
                    <th>Email Subject</th>
                    <th>From Name</th>
                    <th>From Email</th>
                    <th>Template</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($automation->emails as $email)
                    <tr>
                        <td>{{ $email->subject }}</td>
                        <td>{{ $email->from_name }}</td>
                        <td>{{ $email->from_email }}</td>
                        <td>
                            @if($email->template == null)
                                <span class="label label-danger">Not Set</span>
                            @else
                                {{ $email->template->name }}
                            @endif
                        </td>
                        <td>
                            @if($email->content == null)
                                <a href="#">
                                    Edit Content
                                </a>
                            @endif
                        </td>
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
