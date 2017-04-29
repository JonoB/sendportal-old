@extends('common.template')

@section('heading')
    Email Templates
@endsection

@section('content')
    <div class="actions-container">
        <a class="btn btn-primary btn-flat pull-right" href="{{ route('templates.create') }}">Create Template</a>
        <div class="clearfix"></div>
    </div>

    @foreach($templates as $template)
        <tr>
            <td>{{ $template->email }}</td>
            <td>{{ $template->first_name }}</td>
            <td>{{ $template->last_name }}</td>
        </tr>
    @endforeach
@endsection