{!! Form::textField('name', 'Campaign Name') !!}
{!! Form::textField('subject', 'Email Subject') !!}
{!! Form::textField('from_name', 'From Name') !!}
{!! Form::textField('from_email', 'From Email') !!}
{!! Form::selectField('template_id', 'Template', $templates, $campaign->template_id ?? null) !!}

@if ($providers->count() === 1)
    {!! Form::hidden('provider_id', $providers->first()->id) !!}
@else
    {!! Form::selectField('provider_id', 'Provider', $providers->pluck('name', 'id'), isset($campaign->provider_id) ? $campaign->provider_id : null) !!}
@endif

{!! Form::submitButton('Save and continue') !!}
{!! Form::close() !!}