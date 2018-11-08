@extends('common.template')

@section('heading')
    Campaigns
@endsection

@section('content')
    <div class="actions-container">
        <a class="btn btn-primary btn-flat pull-right" href="{{ route('campaigns.create') }}">Create Campaign</a>
        <div class="clearfix"></div>
    </div>

    <div class="box box-primary">
        <div class="box-body no-padding">
            <table class="table table-bordered table-responsive">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Template</th>
                    <th>Sent</th>
                    <th>Opened</th>
                    <th>Clicked</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($campaigns as $campaign)
                    <tr>
                        <td>
                            @if ( ! isset($campaign->email) || $campaign->status_id == \App\Models\CampaignStatus::STATUS_DRAFT)
                                <a href="{{ route('campaigns.edit', $campaign->id) }}">{{ $campaign->name }}</a>
                            @else
                                <a href="{{ route('campaigns.report', $campaign->id) }}">{{ $campaign->name }}</a>
                            @endif
                        </td>
                        <td>
                            @if($campaign->status_id === \App\Models\CampaignStatus::STATUS_DRAFT)
                                <span class="label label-default">{{ $campaign->status->name }}</span>
                            @elseif($campaign->status_id === \App\Models\CampaignStatus::STATUS_QUEUED)
                                <span class="label label-warning">{{ $campaign->status->name }}</span>
                            @elseif($campaign->status_id === \App\Models\CampaignStatus::STATUS_SENDING)
                                <span class="label label-info">{{ $campaign->status->name }}</span>
                            @elseif($campaign->status_id === \App\Models\CampaignStatus::STATUS_SENT)
                                <span class="label label-success">{{ $campaign->status->name }}</span>
                            @endif
                        </td>

                        @if(isset($campaign->email))
                            <td>{{ $campaign->email->template->name }}</td>
                            <td>{{ formatValue($campaign->email->sent_count) }}</td>
                            <td>{{ number_format($campaign->email->open_ratio * 100, 1) . '%' }}</td>
                            <td>{{ number_format($campaign->email->click_ratio * 100, 1) . '%' }}</td>
                            <td>
                                @if($campaign->email->content === null)
                                    <a href="{{ route('campaigns.emails.content.edit', [$campaign->id, $campaign->email->id]) }}">
                                        Edit Content
                                    </a>
                                @endif
                            </td>
                        @else
                            <td>N/A</td>
                            <td>N/A</td>
                            <td>N/A</td>
                            <td>N/A</td>
                            <td><a href="{{ route('emails.create', ['campaign' => $campaign->id]) }}">Update</a>
                            </td>
                        @endif
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
