@extends('common.template')

@section('title', 'Configurations')

@section('heading')
    Providers
@endsection

@section('actions')
    <a class="btn btn-primary btn-sm btn-flat" href="{{ route('providers.create') }}">
        <i class="fa fa-plus"></i> Add Provider
    </a>
@endsection

@section('content')
    <div class="box box-primary">
        <div class="box-body no-padding">
            <table class="table table-bordered table-responsive">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Provider</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($providers as $provider)
                        <tr>
                            <td>{{ $provider->name }}</td>
                            <td>{{ $provider->type->name }}</td>
                            <td>
                                <a href="{{ route('providers.edit', $provider->id) }}">Edit</a>
                                <form action="{{ route('providers.delete', $provider->id) }}" method="POST" style="display: inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn-link">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="100%">
                                <h5 class="text-center text-muted">You have not configured any providers.</h5>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
