@extends('common.template')

@section('heading')
    Create Autoresponder
@stop

@section('content')

    {!! Form::open(array('route' => array('autoresponders.store'))) !!}

    @include('autoresponders.partials.form')

@stop
