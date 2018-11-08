@extends('common.template')

@section('title', 'Segments')

@section('heading')
    Segments
@endsection

@section('actions')
    <a class="btn btn-primary btn-sm btn-flat" href="{{ route('segments.create') }}">
        <i class="fa fa-plus"></i> Create Segment
    </a>
@endsection

@section('content')

    <div class="box box-primary">
        <div class="box-body no-padding">
            <table class="table table-bordered table-responsive">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Subscribers</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($segments as $segment)
                        <tr>
                            <td>
                                <a href="{{ route('segments.edit', $segment->id) }}">
                                    {{ $segment->name }}
                                </a>
                            </td>
                            <td>{{ $segment->subscribers_count }}</td>
                            <td><a href="{{ route('segments.edit', $segment->id) }}">Edit</a></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="100%">
                                <h5 class="text-center text-muted">There are no Segments</h5>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
