@extends('common.template')

@section('heading')
    Edit Contact : {{ $contact->name }}
@stop

@section('content')

    {!! Form::model($contact, array('method' => 'put', 'route' => array('contacts.update', $contact->id))) !!}

    @include('contacts.partials.form')

@stop