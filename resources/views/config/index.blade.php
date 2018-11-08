@extends('common.template')

@section('heading')
    Providers
@endsection

@section('actions')
    <a class="btn btn-primary btn-sm btn-flat" href="{{ route('config.create') }}">
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
                    @foreach($configurations as $configuration)
                        <tr>
                            <td>{{ $configuration->name }}</td>
                            <td>{{ $configuration->type->name }}</td>
                            <td><a href="{{ route('config.edit', $configuration->id) }}">Edit</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
