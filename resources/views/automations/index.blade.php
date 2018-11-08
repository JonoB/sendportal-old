@extends('common.template')

@section('title', 'Automations')

@section('heading')
    Automations
@endsection

@section('actions')
    <a class="btn btn-primary btn-sm btn-flat" href="{{ route('automations.create') }}">
        <i class="fa fa-plus"></i> New Automation
    </a>
@endsection

@section('content')

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
                    @foreach($automations as $automation)
                        <td><a href="{{ route('automations.show', ['id' => $automation->id]) }}">{{ $automation->name }}</a></td>
                        <td>{{ $automation->segment->name }}</td>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
