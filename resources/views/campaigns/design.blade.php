@extends('common.template')

@section('heading')
    Campaign Design
@stop

@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ route('campaigns.index') }}"><i class="fa fa-send"></i> Campaigns</a></li>
        <li class="active">{{ $campaign->name }} : Campaign Design</li>
      </ol>
@endsection

@section('content')

    {!! Form::model($campaign->email, array('method' => 'put', 'route' => array('campaigns.design.update', $campaign->id))) !!}

    @include('templates.partials.editor')

    <br>

    <a href="{{ route('campaigns.template', $campaign->id) }}" class="btn btn-link"><i class="fa fa-arrow-left"></i> Back</a>

    <button class="btn btn-primary" type="submit">Save and continue</button>

    {!! Form::close() !!}
@stop
