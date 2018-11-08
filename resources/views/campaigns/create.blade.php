@extends('common.template')

@section('title', 'Create Campaign')

@section('heading')
    Create Campaign
@stop

@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ route('campaigns.index') }}"><i class="fa fa-send"></i> Campaigns</a></li>
        <li class="active">Create Campaign</li>
      </ol>
@endsection

@section('content')

	@if( ! $templatesAvailable)
        <div class="callout callout-danger">
            <h4>You haven't created any templates!</h4>
            <p>Before you can create a campaign, you must first <a href="{{ route('templates.create') }}">create a
                    template</a>.
            </p>
        </div>
    @else
    	{!! Form::open(['route' => ['campaigns.store'], 'class' => 'form-horizontal']) !!}

    	@include('campaigns.partials.form')
	@endif
@stop
