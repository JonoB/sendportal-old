@extends('common.template')

@section('heading')
    Newsletter Design
@stop

@section('content')

    {!! Form::model($newsletter, array('method' => 'put', 'route' => array('newsletters.design.update', $newsletter->id))) !!}

    @include('templates.partials.editor')

    {!! Form::submitButton('Save and continue') !!}
    {!! Form::close() !!}

@stop
