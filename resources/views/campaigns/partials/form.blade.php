<div class="row">
    <div class="col-sm-6">
        {!! Form::textField('name', 'Campaign Name', isset($campaign->name) ? $campaign->name : null) !!}

        {!! Form::submitButton('Save and continue') !!}
        {!! Form::close() !!}
    </div>
</div>
