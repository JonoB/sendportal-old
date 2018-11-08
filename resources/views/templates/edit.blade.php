@extends('common.template')

@section('heading')
    Edit Template : {{ $template->name }}
@stop

@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ route('templates.index') }}"><i class="fa fa-file-text"></i> Templates</a></li>
        <li class="active">Edit Template : {{ $template->name }}</li>
      </ol>
@endsection

@section('content')

    {!! Form::model($template, ['method' => 'put', 'route' => ['templates.update', $template->id], 'class' => 'form-horizontal']) !!}

    @include('templates.partials.form')

@stop
