@extends('common.template')

@section('heading')
    Create Campaign
@stop

@section('content')

    @if( ! $templatesAvailable)
        <div class="col-md-6">
            <div class="no-templates">
                <h4>You haven't created any templates!</h4>
                <p>Before you can create a campaign, you must <a href="{{ route('templates.create') }}">create a
                        template</a>.</p>
            </div>
        </div>
    @else
        {!! Form::open(array('route' => array('campaigns.store'))) !!}

        @include('campaigns.partials.form')
    @endif

@stop
