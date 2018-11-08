@extends('common.template')

@section('heading')
    Automations
@endsection

@section('actions')
    <div class="actions-bar">
        <a class="btn btn-primary btn-sm btn-flat" href="{{ route('automations.create') }}">
            <i class="fa fa-plus"></i> New Automation
        </a>
    </div>
@endsection

@section('content')

    <div class="col-sm-8">
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
                        <td>
                            <a href="{{ route('automations.show', ['id' => $automation->id]) }}">{{ $automation->name }}</a>
                        </td>
                        <td>{{ $automation->segment->name }}</td>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
