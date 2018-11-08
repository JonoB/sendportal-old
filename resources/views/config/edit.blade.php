@extends('common.template')

@section('title', "Update : {$configType->name} settings")

@section('heading')
    Update : {{ $configType->name }} settings
@stop

@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ route('config.index') }}"><i class="fa fa-list"></i> Configurations</a></li>
        <li class="active">Update : {{ $configType->name }} settings</li>
      </ol>
@endsection

@section('content')

    {!! Form::open(['method' => 'post', 'route' => ['config.update', $configType->id]]) !!}

    @foreach($configType->fields as $name => $field)

        {!! Form::textField($field, $name, array_get($settings, $field)) !!}

    @endforeach

    {!! Form::submitButton('Update') !!}
    {!! Form::close() !!}

@stop
