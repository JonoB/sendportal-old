@extends('common.template')

@section('heading')
    Autoresponders
@endsection

@section('content')
    <div class="actions-container">
        <a class="btn btn-primary btn-flat pull-right" href="{{ route('autoresponders.create') }}">Create Autoresponder</a>
        <div class="clearfix"></div>
    </div>

    <div class="box box-primary">
        <div class="box-body no-padding">
            <table class="table table-bordered table-responsive">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Segments</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($autoresponders as $autoresponder)
                        <td><a href="{{ route('autoresponders.show', ['id' => $autoresponder->id]) }}">{{ $autoresponder->name }}</a></td>
                        <td>{{ $autoresponder->segment->name }}</td>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
