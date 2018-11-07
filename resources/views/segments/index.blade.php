@extends('common.template')

@section('heading')
    Segments
@endsection

@section('content')
    <div class="actions-container">
        <a class="btn btn-primary btn-flat pull-right" href="{{ route('segments.create') }}">Create Segment</a>
        <div class="clearfix"></div>
    </div>

    <div class="box box-primary">
        <div class="box-body no-padding">
            <table class="table table-bordered table-responsive">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Subscribers</th>
                        <th>Active Subscribers</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($segments as $segment)
                        <tr>
                            <td>
                                <a href="{{ route('segments.edit', $segment->id) }}">
                                    {{ $segment->name }}
                                </a>
                            </td>
                            <td>{{ $segment->subscribers_count }}</td>
                            <td>{{ $segment->active_subscribers_count }}</td>
                            <td><a href="{{ route('segments.edit', $segment->id) }}">Edit</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
