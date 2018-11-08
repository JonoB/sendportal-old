@extends('common.template')

@section('title', "Update : {$configType->name} settings")

@section('heading')
    Update : {{ $configType->name }} settings
@stop

@section('content')

    {!! Form::open(['method' => 'post', 'route' => ['config.update', $config->id]]) !!}

    {!! Form::textField('name', 'Name', $config->name) !!}

    @foreach($configType->fields as $name => $field)

        {!! Form::textField($field, $name, array_get($config->settings, $field)) !!}

    @endforeach

    {!! Form::submitButton('Update') !!}
    {!! Form::close() !!}

@stop
