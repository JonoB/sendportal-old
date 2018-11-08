@extends('common.template')

@section('heading')
    {{ $automation->name }}
@stop

@section('actions')
    <a class="btn btn-primary btn-sm btn-flat"
        href="{{ route('automations.emails.create', ['automation' => $automation->id]) }}"><i class="fa fa-plus"></i> Add Email
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
                @foreach($automation->emails as $email)
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
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop
