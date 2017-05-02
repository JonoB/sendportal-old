@extends('common.template')

@section('heading')
    Newsletter Template
@stop

@section('content')

    {!! Form::model($newsletter, array('method' => 'put', 'route' => array('newsletters.template.update', $newsletter->id))) !!}
    {!! Form::selectField('template_id', 'Template', $templates) !!}

    <a href="{{ route('newsletters.edit', $newsletter->id) }}" class="btn btn-default">Back</a>
    {!! Form::submitButton('Save and continue') !!}
    {!! Form::close() !!}

@stop
