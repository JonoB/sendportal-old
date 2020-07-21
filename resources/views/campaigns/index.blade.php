@extends('layouts.app')

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

    <div class="card">
        <div class="card-table">
            <table class="table">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Sent</th>
                    <th>Opened</th>
                    <th>Clicked</th>
                    <th>Created</th>
                    <th>Status</th>
                    <th>Next Step</th>
                </tr>
                </thead>
                <tbody>
                @forelse($campaigns as $campaign)
                    <tr>
                        <td>
                            @if ($campaign->draft)
                                <a href="{{ route('campaigns.content.edit', $campaign->id) }}">{{ $campaign->name }}</a>
                            @elseif($campaign->sent)
                                <a href="{{ route('campaigns.report', $campaign->id) }}">{{ $campaign->name }}</a>
                            @else
                                <a href="{{ route('campaigns.status', $campaign->id) }}">{{ $campaign->name }}</a>
                            @endif
                        </td>
                        <td>{{ formatValue($campaign->sent_count) }}</td>
                        <td>{{ formatRatio($campaign->open_ratio) }}</td>
                        <td>{{ formatRatio($campaign->click_ratio) }}</td>
                        <td><span title="{{ $campaign->created_at }}">{{ $campaign->created_at->diffForHumans() }}</span></td>
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
                        <td>
                            @if ($campaign->status_id === \App\Models\CampaignStatus::STATUS_DRAFT)
                                <a href="{{ route('campaigns.content.edit', $campaign->id) }}">
                                    Edit Content
                                </a>
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
