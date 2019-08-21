@extends('layouts.subscriptions')

@section('content')
    <div class="text-center">
        <h1>Unsubscribe</h1>
        <p>Remove <b>{{ $subscriber->email }}</b> from this email list?</p>

        <form action="{{ route('subscriptions.update', $subscriber->id) }}" method="post">
            @csrf
            <input type="hidden" name="_method" value="put">
            <input type="hidden" name="unsubscribed" value="1">
            <button type="submit" class="btn btn-sm btn-primary">Unsubscribe now</button>
        </form>
    </div>
@endsection