@extends('common.template')

@section('heading')
    Update : {{ $providerType->name }} settings
@stop

@section('content')

    {!! Form::open(['method' => 'post', 'route' => ['providers.update', $provider->id]]) !!}

    {!! Form::textField('name', 'Name', $provider->name) !!}

    @foreach($providerType->fields as $name => $field)

        {!! Form::textField($field, $name, array_get($provider->settings, $field)) !!}

    @endforeach

    {!! Form::submitButton('Update') !!}
    {!! Form::close() !!}

@stop
