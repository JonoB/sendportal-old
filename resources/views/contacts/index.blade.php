@extends('common.template')

@section('heading')
    Contacts
@endsection

@section('content')
    <div class="actions-container">
        <a class="btn btn-primary btn-flat pull-right" href="{{ route('contacts.create') }}">Create Contact</a>
        <div class="clearfix"></div>
    </div>

    <div class="box box-primary">
        <div class="box-body no-padding">
            <table class="table table-bordered table-responsive">
                <thead>
                    <tr>
                        <th>Email</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($contacts as $contact)
                        <tr>
                            <td>{{ $contact->email }}</td>
                            <td>{{ $contact->first_name }}</td>
                            <td>{{ $contact->last_name }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection