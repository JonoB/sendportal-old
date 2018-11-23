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
                    @forelse($automations as $automation)
                        <td><a href="{{ route('automations.show', ['id' => $automation->id]) }}">{{ $automation->name }}</a></td>
                        <td>{{ $automation->segment->name }}</td>
                    @empty
                        <tr>
                            <td colspan="100%">
                                <h5 class="text-center text-muted">You have not created any automations.</h5>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
