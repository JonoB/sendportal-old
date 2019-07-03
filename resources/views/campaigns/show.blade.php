@extends('layouts.app')

@section('heading')
    Campaign: {{ $campaign->name }}
@stop

@section('content')

    @if ($campaign->email->content ?? false)
        <a href="{{ route('campaigns.confirm', $campaign->id) }}">
            Confirm and Send Campaign
        </a>
    @elseif ($campaign->email)
        <ul>
            <li><a href="{{ route('campaigns.edit', $campaign->id) }}">Edit Campaign</a></li>
            <li><a href="{{ route('campaigns.emails.content.edit', $campaign->id) }}">Edit Content</a></li>
        </ul>
    @else
        <ul>
            <li><a href="{{ route('campaigns.edit', $campaign->id) }}">Edit Campaign</a></li>
            <li><a href="{{ route('campaigns.emails.create', ['id' => $campaign->id]) }}">Create Email</a></li>
        </ul>
    @endif

    {{--<div class="row">--}}
        {{--<div class="col-md-6">--}}
            {{--<h4>Recipients</h4>--}}

{{--            {!! Form::model($campaign, array('method' => 'put', 'route' => array('campaigns.send', $campaign->id))) !!}--}}

            {{--@foreach($lists as $list)--}}
                {{--<div class="checkbox">--}}
                    {{--<label><input name="lists[]" type="checkbox" value="{{ $list->id }}">{{ $list->name }} ({{ $list->subscribers()->count() }} subscribers)</label>--}}
                {{--</div>--}}
            {{--@endforeach--}}

            {{--<h4>Schedule</h4>--}}
            {{--<div class="radio">--}}
                {{--<label>--}}
                    {{--<input type="radio" name="schedule" id="optionsRadios1" value="1" checked>--}}
                    {{--Send immediately--}}
                {{--</label>--}}
            {{--</div>--}}
            {{--<div class="radio">--}}
                {{--<label>--}}
                    {{--<input type="radio" name="schedule" id="optionsRadios2" value="2">--}}
                    {{--Send at a specific time--}}
                {{--</label>--}}
            {{--</div>--}}

{{--            <a href="{{ route('campaigns.design', $campaign->id) }}" class="btn btn-default">Back</a>--}}
            {{--{!! Form::submitButton('Send campaign') !!}--}}
            {{--{!! Form::close() !!}--}}
        {{--</div>--}}
        {{--<div class="col-md-6">--}}
            {{--<form class="form-horizontal">--}}
                {{--<div class="form-group">--}}
                    {{--<label class="col-sm-2 control-label">From</label>--}}
                    {{--<div class="col-sm-10">--}}
                        {{--<p class="form-control-static">{{ $campaign->email->from_name . ' <' . $campaign->email->from_email . '>' }}</p>--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<div class="form-group">--}}
                    {{--<label class="col-sm-2 control-label">Subject</label>--}}
                    {{--<div class="col-sm-10">--}}
                        {{--<p class="form-control-static">{{ $campaign->email->subject }}</p>--}}
                    {{--</div>--}}
                {{--</div>--}}

                {{--<div style="border: 1px solid #ddd; height: 600px">--}}
                    {{--<iframe id="js-template-iframe" srcdoc="{{ $campaign->email->content }}" class="embed-responsive-item" frameborder="0" style="height: 100%; width: 100%"></iframe>--}}
                {{--</div>--}}

            {{--</form>--}}
        {{--</div>--}}
    {{--</div>--}}

@stop
