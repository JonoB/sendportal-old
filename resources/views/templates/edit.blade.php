@extends('common.template')

@section('heading')
    Edit Template : {{ $temaplte->name }}
@stop

@section('content')

    {!! Form::model($contact, array('method' => 'put', 'route' => array('templates.update', $contact->id))) !!}

    @include('templates.partials.form')

@stop