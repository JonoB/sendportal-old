<div class="row">
    <div class="col-sm-6">
        {!! Form::textField('subject', 'Email Subject', isset($automationStep->email->subject) ? $automationStep->email->subject : null) !!}
        {!! Form::textField('from_name', 'From Name', isset($automationStep->email->from_name) ? $automationStep->email->from_name : null) !!}
        {!! Form::textField('from_email', 'From Email', isset($automationStep->email->from_email) ? $automationStep->email->from_email : null) !!}
        {!! Form::selectField('template_id', 'Templates', $templates) !!}

    </div>
</div>