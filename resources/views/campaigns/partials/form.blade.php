<div class="row">
    <div class="col-sm-6">
        {!! Form::textField('name', 'Campaign Name', isset($campaign->name) ? $campaign->name : null) !!}

        @if ($providers->count() === 1)
            {!! Form::hidden('config_id', $providers->first()->id) !!}
        @else
            {!! Form::selectField('config_id', 'Provider', $providers->pluck('name', 'id')) !!}
        @endif

        {!! Form::submitButton('Save and continue') !!}
        {!! Form::close() !!}
    </div>
</div>
