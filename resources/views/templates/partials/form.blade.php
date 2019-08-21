
{!! Form::textField('name', 'Template Name') !!}

@include('templates.partials.editor')

<div class="form-group row">
    <div class="offset-sm-2 col-sm-4">
        <a href="#" class="btn btn-sm btn-secondary btn-preview">Show Preview</a>
        <button class="btn btn-primary btn-sm" type="submit">Save Template</button>
    </div>
</div>


{!! Form::close() !!}
