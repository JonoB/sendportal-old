@extends('common.template')

@section('heading')
    Providers
@endsection

@section('content')
    <div class="actions-container">
        <a class="btn btn-primary btn-flat pull-right" href="{{ route('config.create') }}">Add Provider</a>
        <div class="clearfix"></div>
    </div>

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
