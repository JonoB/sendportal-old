@extends('common.template')

@section('heading')
    Campaigns
@endsection

@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">Campaigns</li>
      </ol>
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
                </tr>
                </thead>
                <tbody>
                @foreach($campaigns as $campaign)
                    <tr>
                        <td>
                            @if ($campaign->email->status_id == \App\Models\CampaignStatus::STATUS_DRAFT)
                                <a href="{{ route('campaigns.edit', $campaign->id) }}">{{ $campaign->name }}</a>
                            @else
                                <a href="{{ route('campaigns.report', $campaign->id) }}">{{ $campaign->name }}</a>
                            @endif
                        </td>
                        <td>
                            @if($campaign->email->status_id == \App\Models\CampaignStatus::STATUS_DRAFT)
                                <span class="label label-default">{{ $campaign->email->status->name }}</span>
                            @elseif($campaign->email->status_id == \App\Models\CampaignStatus::STATUS_QUEUED)
                                <span class="label label-warning">{{ $campaign->email->status->name }}</span>
                            @elseif($campaign->email->status_id == \App\Models\CampaignStatus::STATUS_SENDING)
                                <span class="label label-info">{{ $campaign->email->status->name }}</span>
                            @elseif($campaign->email->status_id == \App\Models\CampaignStatus::STATUS_SENT)
                                <span class="label label-success">{{ $campaign->email->status->name }}</span>
                            @endif
                        </td>
                        <td>{{ $campaign->email->template->name }}</td>
                        <td>{{ formatValue($campaign->email->sent_count) }}</td>
                        <td>{{ number_format($campaign->email->open_ratio * 100, 1) . '%' }}</td>
                        <td>{{ number_format($campaign->email->click_ratio * 100, 1) . '%' }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
