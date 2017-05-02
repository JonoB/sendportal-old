<div class="row">
    <div class="col-sm-6">
        {!! Form::textField('name', 'Template Name') !!}
    </div>
</div>

@include('templates.partials.editor')

{!! Form::submitButton('Save Template') !!}

{!! Form::close() !!}
