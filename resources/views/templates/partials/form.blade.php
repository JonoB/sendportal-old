<div class="row">
    <div class="col-sm-6">
        {!! Form::textField('name', 'Template Name') !!}
    </div>
</div>

@include('templates.partials.editor')

<button class="btn btn-primary" type="submit">Save Template</button>

{!! Form::close() !!}
