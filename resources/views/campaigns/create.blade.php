@extends('layouts.app')

@section('title', 'Create Campaign')

@section('heading', 'Create Campaign')

@section('content')

	@if( ! $providers)
        <div class="callout callout-danger">
            <h4>You haven't added any providers!</h4>
            <p>Before you can create a campaign, you must first <a href="{{ route('providers.create') }}">add a provider</a>.
            </p>
        </div>
    @else
        <div class="card">
            <div class="card-body">
                {!! Form::open(['route' => ['campaigns.store'], 'class' => 'form-horizontal']) !!}

                @include('campaigns.partials.form')
            </div>
        </div>
	@endif
@stop
