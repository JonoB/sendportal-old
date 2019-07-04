{!! Form::textField('subject', 'Email Subject') !!}
{!! Form::textareaField('content', 'Content') !!}
{!! Form::selectField('template_id', 'Template', $templates) !!}
{!! Form::textField('delay', 'Delay') !!}
{!! Form::selectField('delay_type', 'Delay Period', \App\Models\AutomationStep::listFrequencies()) !!}