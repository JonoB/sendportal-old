@extends('common.template')

@section('heading')
    Edit Segment : {{ $segment->name }}
@stop

@section('content')

    {!! Form::model($segment, ['method' => 'put', 'class' => 'form-horizontal', 'route' => ['segments.update', $segment->id]]) !!}

    @include('segments.partials.form')

    @php
        $existingSubscribers = $segment->subscribers->pluck('id');
    @endphp

    @foreach($subscribers as $subscriber)
        @if ($existingSubscribers->contains($subscriber->id))
            @php
                $existing = $segment->subscribers->first(function ($existing) use ($subscriber) {
                    return $existing->id === $subscriber->id;
                });

                $label = $subscriber->full_name . ($existing->pivot->unsubscribed_at ? ' (' . $existing->pivot->unsubscribed_at . ')' : '');
            @endphp
            {!! Form::checkboxField('subscribers[]',  $label, $subscriber->id) !!}
        @else
            {!! Form::checkboxField('subscribers[]', $subscriber->full_name, $subscriber->id) !!}
        @endif
    @endforeach

    {!! Form::submitButton('Save') !!}

    {!! Form::close() !!}
@stop
