@extends('common.template')

@section('title', 'Create Campaign')

@section('heading')
    Create Campaign
@stop

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
