@extends('common.template')

@section('heading')
    Newsletters
@endsection

@section('content')
    <div class="actions-container">
        <a class="btn btn-primary btn-flat pull-right" href="{{ route('newsletters.create') }}">Create Newsletter</a>
        <div class="clearfix"></div>
    </div>

    <div class="box box-primary">
        <div class="box-body no-padding">
            <table class="table table-bordered table-responsive">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Template</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>
                @foreach($newsletters as $newsletter)
                    <tr>
                        <td>{{ $newsletter->name }}</td>
                        <td>{{ $newsletter->template->name or '' }}</td>
                        <td>
                            @if($newsletter->newsletter_status_id == \App\Models\Newsletter::STATUS_DRAFT)
                                <span class="label label-default">Draft</span>
                            @elseif($newsletter->newsletter_status_id == \App\Models\Newsletter::STATUS_SENDING)
                                <span class="label label-warning">Sending</span>
                            @elseif($newsletter->newsletter_status_id == \App\Models\Newsletter::STATUS_SENT)
                                <span class="label label-success">Sent</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
