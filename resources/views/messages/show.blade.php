@extends('layouts.app')

@section('title', 'Message')

@section('heading', 'Message')

@section('content')

    <div class="card">
        <div class="card-header card-header-accent">
            <div class="card-header-inner">
                <div class="float-left">
                    <b>To:</b> {{ $message->recipient_email }}<br>
                    <b>Subject:</b> {{ $message->subject }}<br>
                    <b>From:</b> {{ $message->from_name }} &lt;{{ $message->from_email }}&gt;
                </div>
                <div class="float-right">
                    @if ($message->sent_at)
                        Sent {{ $message->sent_at->diffForHumans() }}
                    @else
                        <form action="{{ route('messages.send') }}" method="post">
                            @csrf
                            <input type="hidden" name="id" value="{{ $message->id }}">
                            <button type="submit" class="btn btn-sm btn-primary">Send now</button>
                        </form>
                    @endif
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
        <div class="card-body">
            {!! $content !!}
        </div>
    </div>

@endsection



