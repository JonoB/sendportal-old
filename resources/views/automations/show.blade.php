@extends('common.template')

@section('heading')
    {{ $automation->name }}
@stop

@section('content')
    <div class="actions-container">
        <a class="btn btn-primary btn-flat pull-right"
           href="{{ route('automations.emails.create', ['automation' => $automation->id]) }}">Add Email</a>
        <div class="clearfix"></div>
    </div>

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
                                <a href="{{ route('emails.design', ['id' => $email->id]) }}">
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
