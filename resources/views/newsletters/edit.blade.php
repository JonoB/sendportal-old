@extends('common.template')

@section('heading')
    Edit Newsletter
@stop

@section('content')

    {!! Form::model($newsletter, array('method' => 'put', 'route' => array('newsletters.update', $newsletter->id))) !!}


    @include('newsletters.partials.form')

@stop
