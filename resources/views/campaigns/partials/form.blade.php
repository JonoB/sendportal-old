{!! Form::textField('name', 'Campaign Name', isset($campaign->name) ? $campaign->name : null) !!}

@if ($providers->count() === 1)
    {!! Form::hidden('provider_id', $providers->first()->id) !!}
@else
    {!! Form::selectField('provider_id', 'Provider', $providers->pluck('name', 'id'), isset($campaign->provider_id) ? $campaign->provider_id : null) !!}
@endif

{!! Form::submitButton('Save and continue') !!}
{!! Form::close() !!}