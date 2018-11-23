@extends('common.template')

@section('title', 'Subscribers')

@section('heading')
    Subscribers
@endsection

@section('actions')
    <div class="btn-group">
        <button class="btn btn-sm btn-default dropdown-toggle" type="button" data-toggle="dropdown">
            <i class="fa fa-bars"></i>
        </button>
        <ul class="dropdown-menu">
            <li>
                <a href="{{ route('subscribers.import') }}">
                    <i class="fa fa-upload"></i> Import Subscribers
                </a>
            </li>
            <li>
                <a href="{{ route('subscribers.export') }}">
                    <i class="fa fa-download"></i> Export Subscribers
                </a>
            </li>
        </ul>
    </div>
    <a class="btn btn-primary btn-sm btn-flat" href="{{ route('subscribers.create') }}">
        <i class="fa fa-plus"></i> Add Subscriber
    </a>
@endsection

@section('content')
    <div class="box box-primary">
        <div class="box-body no-padding">
            <table class="table table-bordered table-responsive">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Segments</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subscribers as $subscriber)
                        <tr>
                            <td>
                                <a href="{{ route('subscribers.show', $subscriber->id) }}">
                                    {{ $subscriber->full_name }}
                                </a>
                            </td>
                            <td><a href="mailto:{{ $subscriber->email }}">{{ $subscriber->email }}</a></td>
                            <td>
                                @foreach($subscriber->segments as $segment)
                                    <span class="label label-default">{{ $segment->name }}</span>
                                @endforeach
                            </td>
                            <td><a href="{{ route('subscribers.edit', $subscriber->id) }}">Edit</a></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="100%">
                                <h5 class="text-center text-muted">You have not added any subscribers.</h5>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {!! $subscribers->links() !!}
        </div>
    </div>
@endsection
