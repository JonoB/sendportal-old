@extends('common.template')

@section('heading')
    Newsletters
@endsection

@section('content')
    <div class="actions-container">
        <a class="btn btn-primary btn-flat pull-right" href="{{ route('newsletters.create') }}">Create Newsletter</a>
        <div class="clearfix"></div>
    </div>

    <div class="box box-primary">
        <div class="box-body no-padding">
            <table class="table table-bordered table-responsive">
                <thead>
                <tr>
                    <th>Name</th>
                </tr>
                </thead>
                <tbody>
                @foreach($newsletters as $newsletter)
                    <tr>
                        <td>{{ $newsletter->name }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
