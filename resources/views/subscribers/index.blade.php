@extends('common.template')

@section('heading')
    Subscribers
@endsection

@section('content')
    <div class="actions-container">
        <a class="btn btn-primary btn-flat pull-right" href="{{ route('subscribers.create') }}">Create Subscriber</a>
        <div class="clearfix"></div>
    </div>

    <div class="box box-primary">
        <div class="box-body no-padding">
            <table class="table table-bordered table-responsive">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Segments</th>
                        <th>Tags</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($subscribers as $subscriber)
                        <tr>
                            <td>
                                <a href="{{ route('subscribers.show', $subscriber->id) }}">
                                    {{ $subscriber->full_name }}
                                </a>
                            </td>
                            <td><a href="mailto:{{ $subscriber->email }}">{{ $subscriber->email }}</a></td>
                            <td>{{ $subscriber->segments->count() }}</td>
                            <td>
                                @foreach($subscriber->tags as $tag)
                                    <span class="label label-default">{{ $tag->name }}</span>
                                @endforeach
                            </td>
                            <td><a href="{{ route('subscribers.edit', $subscriber->id) }}">Edit</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {!! $subscribers->links() !!}
        </div>
    </div>
@endsection
