@extends('common.template')

@section('heading')
    Subscriber : {{ $subscriber->first_name }} {{ $subscriber->last_name }}
@stop

@section('content')

    <h4>{{ $subscriber->segments()->count() }} Segments</h4>

    <ul>
        @foreach ($subscriber->segments as $segment)
            <li><a href="{{ route('segments.edit', $segment->id) }}">{{ $segment->name }}</a></li>
        @endforeach
    </ul>

@stop
