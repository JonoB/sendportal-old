@extends('common.template')

@section('heading')
    Campaign: {{ $campaign->name }}
@stop

@section('content')

    @if ($campaign->content ?? false)
        <a href="{{ route('campaigns.confirm', $campaign->id) }}">
            Confirm and Send Campaign
        </a>
    @else
        <ul>
            <li><a href="{{ route('campaigns.edit', $campaign->id) }}">Edit Campaign</a></li>
            <li><a href="{{ route('campaigns.create', ['id' => $campaign->id]) }}">Create Email</a></li>
        </ul>
    @endif

@stop
