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
                    <th>Sent</th>
                    <th>Opened</th>
                    <th>Clicked</th>
                </tr>
                </thead>
                <tbody>
                @foreach($newsletters as $newsletter)
                    <tr>
                        <td>
                            {{ $newsletter->name }}
                        </td>
                        <td>{{ $newsletter->template->name or '' }}</td>
                        <td>
                            @if($newsletter->status_id == \App\Models\NewsletterStatus::STATUS_DRAFT)
                                <span class="label label-default">{{ $newsletter->status->name }}</span>
                                <a href="{{ route('newsletters.edit', $newsletter->id) }}">Edit</a>
                            @elseif($newsletter->status_id == \App\Models\NewsletterStatus::STATUS_QUEUED)
                                <span class="label label-warning">{{ $newsletter->status->name }}</span>
                            @elseif($newsletter->status_id == \App\Models\NewsletterStatus::STATUS_SENDING)
                                <span class="label label-info">{{ $newsletter->status->name }}</span>
                            @elseif($newsletter->status_id == \App\Models\NewsletterStatus::STATUS_SENT)
                                <span class="label label-success">{{ $newsletter->status->name }}</span>
                            @endif
                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
