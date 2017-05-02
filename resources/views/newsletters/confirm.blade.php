@extends('common.template')

@section('heading')
    Confirm Newsletter
@stop

@section('content')

<div class="row">
    <div class="col-md-6">
        <h4>Recipients</h4>

        {!! Form::model($newsletter, array('method' => 'put', 'route' => array('newsletters.send', $newsletter->id))) !!}

        @foreach($segments as $segment)
            <div class="checkbox">
                <label><input name="segments[]" type="checkbox" value="{{ $segment->id }}">{{ $segment->name }} (show count)</label>
            </div>
        @endforeach

        <h4>Schedule</h4>
        <div class="radio">
            <label>
                <input type="radio" name="schedule" id="optionsRadios1" value="1" checked>
                Send immediately
            </label>
        </div>
        <div class="radio">
            <label>
                <input type="radio" name="schedule" id="optionsRadios2" value="2">
                Send at a specific time
            </label>
        </div>

        <a href="{{ route('newsletters.design', $newsletter->id) }}" class="btn btn-default">Back</a>
        {!! Form::submitButton('Send newsletter') !!}
        {!! Form::close() !!}
    </div>
    <div class="col-md-6">
        <form class="form-horizontal">
            <div class="form-group">
                <label class="col-sm-2 control-label">From</label>
                <div class="col-sm-10">
                    <p class="form-control-static">{{ $newsletter->from_name . ' <' . $newsletter->from_email . '>' }}</p>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">Subject</label>
                <div class="col-sm-10">
                    <p class="form-control-static">{{ $newsletter->subject }}</p>
                </div>
            </div>

            <div style="border: 1px solid #ddd; height: 600px">
                <iframe id="js-template-iframe" srcdoc="{{ $newsletter->content }}" class="embed-responsive-item" frameborder="0" style="height: 100%; width: 100%"></iframe>
            </div>

        </form>
    </div>
</div>

@stop
