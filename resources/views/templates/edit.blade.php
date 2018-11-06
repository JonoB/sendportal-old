@extends('common.template')

@section('heading')
    Edit Template : {{ $template->name }}
@stop

@section('content')

    {!! Form::model($template, ['method' => 'put', 'route' => ['templates.update', $template->id], 'class' => 'form-horizontal']) !!}

    @include('templates.partials.form')

@stop
