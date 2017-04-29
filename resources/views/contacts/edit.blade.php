@extends('common.template')

@section('heading')
    Edit Contact : {{ $contact->name }}
@stop

@section('content')

    {!! Form::model($contact, array('method' => 'put', 'route' => array('admin.contacts.update', $contacts->id))) !!}

    @include('contacts.partials.form')

    {!! Form::close() !!}}
@stop