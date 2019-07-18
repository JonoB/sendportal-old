@extends('layouts.app')

@section('title', 'Sent')

@section('heading', 'Deliveries')

@section('content')

    @include('messages.partials.nav')

    <div class="card">
        <div class="card-table">
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Subject</th>
                        <th>Recipient</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($messages as $message)
                        <tr>
                            <td>
                                {{ $message->created_at }}
                            </td>
                            <td>{{ $message->subject }}</td>
                            <td><a href="{{ route('subscribers.show', $message->subscriber_id) }}">{{ $message->recipient_email }}</a></td>
                            <td>
                                @if ( ! $message->sent_at)
                                    <form action="{{ route('messages.send') }}" method="post">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $message->id }}">
                                        <button type="submit" class="btn btn-xs btn-light">Send now</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="100%">
                                <h5 class="text-center text-muted">There are no messages</h5>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {!! $messages->links() !!}
        </div>
    </div>
@endsection
