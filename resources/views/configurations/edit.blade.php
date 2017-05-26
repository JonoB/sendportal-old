@extends('common.template')

@section('heading')
    Update : {{ $configType->name }} settings
@stop

@section('content')

    {!! Form::open(['method' => 'post', 'route' => ['configurations.update', $configType->id]]) !!}

    @foreach($configType->fields as $name => $field)

        {!! Form::textField($field, $name, array_get($settings, $field)) !!}

    @endforeach

    {!! Form::submitButton('Update') !!}
    {!! Form::close() !!}

@stop
