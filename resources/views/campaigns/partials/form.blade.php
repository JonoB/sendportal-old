<div class="row">
    <div class="col-sm-6">
        {!! Form::textField('name', 'Campaign Name', isset($campaign->name) ? $campaign->name : null) !!}
        {!! Form::textField('subject', 'Email Subject', isset($campaign->email->subject) ? $campaign->email->subject : null) !!}
        {!! Form::textField('from_name', 'From Name', isset($campaign->email->from_name) ? $campaign->email->from_name : null) !!}
        {!! Form::textField('from_email', 'From Email', isset($campaign->email->from_email) ? $campaign->email->from_email : null) !!}

        {!! Form::submitButton('Save and continue') !!}
        {!! Form::close() !!}

    </div>


</div>
