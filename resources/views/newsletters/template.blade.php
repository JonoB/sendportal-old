@extends('common.template')

@section('heading')
    Newsletter Template
@stop

@section('content')

    {!! Form::model($newsletter, array('method' => 'put', 'route' => array('newsletters.template.update', $newsletter->id))) !!}
    {!! Form::selectField('template_id', 'Template', $templates) !!}
    {!! Form::submitButton() !!}
    {!! Form::close() !!}

@stop
