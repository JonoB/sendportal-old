@extends('common.template')

@section('heading')
    {{ $automation->name }}
@stop

@section('actions')
    <a class="btn btn-primary btn-sm btn-flat"
       href="{{ route('automations.steps.create', ['automation' => $automation->id]) }}"><i class="fa fa-plus"></i> Add
        Automation Step
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
                @foreach($automation->steps as $step)
                    <tr>
                        <td>{{ $step->email->subject }}</td>
                        <td>{{ $step->email->from_name }}</td>
                        <td>{{ $step->email->from_email }}</td>
                        <td>
                            @if($step->email->template == null)
                                <span class="label label-danger">Not Set</span>
                            @else
                                {{ $step->email->template->name }}
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('automations.steps.email.content.edit', [$automation->id, $step->id]) }}">
                                Edit Content
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop
