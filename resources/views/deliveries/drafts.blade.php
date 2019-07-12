@extends('layouts.app')

@section('title', 'Draft Deliveries')

@section('heading', 'Deliveries')

@section('content')

    @include('deliveries.partials.nav')

    <div class="card">
        <div class="card-table">
            <table class="table">
                <thead>
                    <tr>
                        <th>Created</th>
                        <th>Subject</th>
                        <th>Recipient</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($deliveries as $delivery)
                        <tr>
                            <td>
                                {{ $delivery->created_at }}
                            </td>
                            <td>{{ $delivery->subject }}</td>
                            <td>{{ $delivery->recipient_email }}</td>
                            <td>
                                @if ( ! $delivery->sent_at)
                                    <a href="" class="btn btn-sm btn-light">Send now</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="100%">
                                <h5 class="text-center text-muted">There are no deliveries yet</h5>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {!! $deliveries->links() !!}
        </div>
    </div>
@endsection
