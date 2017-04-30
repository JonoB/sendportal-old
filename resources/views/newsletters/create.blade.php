@extends('common.template')

@section('heading')
    Create Newsletter
@stop

@section('content')

    {!! Form::open(array('route' => array('newsletters.store'))) !!}

    @include('newsletters.partials.form')

@stop
