@extends('common.template')

@section('heading')
    Subscriber : {{ $subscriber->full_name }}
@stop

@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ route('subscribers.index') }}"><i class="fa fa-users"></i> Subscribers</a></li>
        <li class="active">Subscriber : {{ $subscriber->full_name }}</li>
      </ol>
@endsection

@section('content')

    <div class="row">
        <div class="col-md-6">
            <h4>{{ $subscriber->segments()->count() }} Segments</h4>

            <ul>
                @foreach ($subscriber->segments as $segment)
                    <li><a href="{{ route('segments.edit', $segment->id) }}">{{ $segment->name }}</a></li>
                @endforeach
            </ul>
        </div>
    </div>

@stop
