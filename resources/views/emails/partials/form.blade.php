{!! Form::textField('subject', 'Email Subject', isset($campaign->email->subject) ? $campaign->email->subject : null) !!}
{!! Form::textField('from_name', 'From Name', isset($campaign->email->from_name) ? $campaign->email->from_name : null) !!}
{!! Form::textField('from_email', 'From Email', isset($campaign->email->from_email) ? $campaign->email->from_email : null) !!}
{!! Form::selectField('template_id', 'Templates', $templates) !!}
