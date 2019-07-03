@extends('common.template')

@section('title', 'Campaigns')

@section('heading')
    Campaigns
@endsection

@section('actions')
    <a class="btn btn-primary btn-sm btn-flat" href="{{ route('campaigns.create') }}">
        <i class="fa fa-plus"></i> Create Campaign
    </a>
@endsection

@section('content')

    <div class="box box-primary">
        <div class="box-body no-padding">
            <table class="table table-bordered table-responsive">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Sent</th>
                    <th>Opened</th>
                    <th>Clicked</th>
                    <th>Next Step</th>
                </tr>
                </thead>
                <tbody>
                @forelse($campaigns as $campaign)
                    <tr>
                        <td>
                            @if ($campaign->draft)
                                @if( ! $campaign->template)
                                    <a href="{{ route('campaigns.template.create', $campaign->id) }}">{{ $campaign->name }}</a>
                                @else
                                    <a href="{{ route('campaigns.content.edit', $campaign->id) }}">{{ $campaign->name }}</a>
                                @endif
                            @elseif($campaign->sent)
                                <a href="{{ route('campaigns.report', $campaign->id) }}">{{ $campaign->name }}</a>
                            @else
                                <a href="{{ route('campaigns.status', $campaign->id) }}">{{ $campaign->name }}</a>
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
                        <td>{{ formatValue($campaign->sent_count) }}</td>
                        <td>{{ formatRatio($campaign->open_ratio) }}</td>
                        <td>{{ formatRatio($campaign->click_ratio) }}</td>
                        <td>
                            @if ($campaign->status_id === \App\Models\CampaignStatus::STATUS_DRAFT)
                                @if($campaign->template_id === null)
                                    <a href="{{ route('campaigns.template.create', $campaign->id) }}">
                                        Select Template
                                    </a>
                                @else
                                    <a href="{{ route('campaigns.content.edit', $campaign->id) }}">
                                        Edit Content
                                    </a>
                                @endif
                            @else
                                N/A
                            @endif
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="100%">
                            <h5 class="text-center text-muted">You have not created any campaigns.</h5>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
