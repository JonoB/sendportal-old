<div class="row">
    <div class="col-sm-6">
        {!! Form::textField('name', 'Campaign') !!}
        {!! Form::textField('subject', 'Email Subject') !!}
        {!! Form::textField('from_name', 'From Name') !!}
        {!! Form::textField('from_email', 'From Email') !!}
        {!! Form::checkboxField('track_opens', 'Track Opens') !!}
        {!! Form::checkboxField('track_clicks', 'Track Clicks') !!}

        {!! Form::submitButton('Save and continue') !!}
        {!! Form::close() !!}

    </div>


</div>
