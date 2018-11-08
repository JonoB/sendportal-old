<div class="row">
    <div class="col-sm-6">
        {!! Form::textField('name', 'Automation Name') !!}
        {!! Form::selectField('segment_id', 'Segment', $segments) !!}

        {!! Form::submitButton('Save and continue') !!}
        {!! Form::close() !!}
    </div>
</div>
