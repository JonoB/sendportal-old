@extends('common.template')

@section('heading')
    Automations
@endsection

@section('content')
    <div class="actions-container">
        <a class="btn btn-primary btn-flat pull-right" href="{{ route('automations.create') }}">New Automation</a>
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
                    @foreach($automations as $automation)
                        <td><a href="{{ route('automations.show', ['id' => $automation->id]) }}">{{ $automation->name }}</a></td>
                        <td>{{ $automation->segment->name }}</td>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
