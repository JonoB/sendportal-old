@extends('common.template')

@section('heading')
    Lists
@endsection

@section('content')
    <div class="actions-container">
        <a class="btn btn-primary btn-flat pull-right" href="{{ route('lists.create') }}">Create List</a>
        <div class="clearfix"></div>
    </div>

    <div class="box box-primary">
        <div class="box-body no-padding">
            <table class="table table-bordered table-responsive">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($subscriberLists as $list)
                        <tr>
                            <td>
                                <a href="{{ route('lists.subscribers.index', $list->id) }}">
                                    {{ $list->name }}
                                </a>
                            </td>
                            <td><a href="{{ route('lists.edit', $list->id) }}">Edit</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
