@extends('common.template')

@section('heading')
    Newsletter Design
@stop

@section('content')

    {!! Form::model($newsletter, array('method' => 'put', 'route' => array('newsletters.design.update', $newsletter->id))) !!}

    <textarea name="content" id="" cols="30" rows="10">
        {!! $newsletter->content ?: $template->content !!}
    </textarea>

    {!! Form::submitButton() !!}
    {!! Form::close() !!}

@stop
