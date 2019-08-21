@extends('layouts.subscriptions')

@section('content')
    <div class="text-center">
        <h1>Resubscribe</h1>
        <p>Add <b>{{ $subscriber->email }}</b> to this email list?</p>

        <form action="{{ route('subscriptions.update', $subscriber->id) }}" method="post">
            @csrf
            <input type="hidden" name="_method" value="put">
            <input type="hidden" name="unsubscribed" value="0">
            <button type="submit" class="btn btn-sm btn-primary">Resubscribe now</button>
        </form>
    </div>
@endsection