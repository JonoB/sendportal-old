@extends('common.template')

@section('heading')
    {{ $automation->name }}
@stop

@section('actions')
    <div class="action-bar">
        <a class="btn btn-primary btn-sm btn-flat"
           href="{{ route('automations.steps.create', ['automation' => $automation->id]) }}"><i class="fa fa-plus"></i>
            Add
            Automation Step
        </a>
    </div>
@endsection

@section('content')

    <div class="col-sm-4">
        <div class="box box-primary">
            <div class="box-body automation-details">
                <h4>Details</h4>
                <hr>
                <ul>
                    <li><strong>Segment:</strong> {{ $automation->segment->name }} ({{ $automation->segment->subscribers_count }} subscribers)</li>
                    <li><strong>Created:</strong> {{ $automation->created_at->toFormattedDateString() }}</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-sm-8">
        <div class="box box-primary">
            <div class="box-body no-padding">
                <table class="table table-bordered table-responsive">
                    <thead>
                    <tr>
                        <th>Email Subject</th>
                        <th>Sends</th>
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
                            <td>{{ $step->sends }}</td>
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
                                <a class="fa fa-cog" href="{{ route('automations.steps.edit', [$automation->id, $step->id]) }}">

                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop
