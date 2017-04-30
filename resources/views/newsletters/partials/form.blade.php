<div class="row">
    <div class="col-sm-6">
        {!! Form::textField('name', 'Newsletter Name') !!}
        {!! Form::textField('subject', 'Email Subject') !!}
        {!! Form::textField('from_name', 'From Name') !!}
        {!! Form::textField('from_email', 'From Email') !!}

        {!! Form::submitButton() !!}

        {!! Form::close() !!}
    </div>
</div>
