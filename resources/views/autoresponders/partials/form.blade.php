<div class="row">
    <div class="col-sm-6">
        {!! Form::textField('name', 'Automation Workflow Name') !!}
        {!! Form::select('segment_id', $segments) !!}

        {!! Form::submitButton('Save and continue') !!}
        {!! Form::close() !!}
    </div>
</div>
