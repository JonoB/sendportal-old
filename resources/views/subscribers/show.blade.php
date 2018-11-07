@extends('common.template')

@section('heading')
    Subscriber : {{ $subscriber->full_name }}
@stop

@section('content')

    <div class="row">
        <div class="col-md-6">
            <h4>{{ $subscriber->segments()->count() }} Segments</h4>

            <ul>
                @foreach ($subscriber->segments as $segment)
                    <li>
                        @if($segment->pivot->unsubscribed_at)
                            <del><a href="{{ route('segments.edit', $segment->id) }}">{{ $segment->name }}</a></del>
                        @else
                            <a href="{{ route('segments.edit', $segment->id) }}">{{ $segment->name }}</a>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="col-md-6">
            <h4>{{ $subscriber->tags()->count() }} Tags</h4>

            <ul>
                @foreach ($subscriber->tags as $tag)
                    <li>{{ $tag->name }}</li>
                @endforeach
            </ul>
        </div>
    </div>

@stop
