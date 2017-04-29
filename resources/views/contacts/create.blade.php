@extends('common.template')

@section('heading')
    New Contact
@stop

@section('content')

    {!! Form::open(array('route' => array('contacts.store'))) !!}

    @include('contacts.partials.form')

@stop