@extends('common.template')

@section('heading')
    New Segment
@stop

@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ route('segments.index') }}"><i class="fa fa-list"></i> Segments</a></li>
        <li class="active">New Segment</li>
      </ol>
@endsection

@section('content')

    {!! Form::open(['route' => ['segments.store'], 'class' => 'form-horizontal']) !!}

    @include('segments.partials.form')

@stop
