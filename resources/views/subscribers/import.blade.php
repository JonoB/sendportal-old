@extends('common.template')

@section('heading')
    Import Subscribers
@stop

@section('content')

    <h4>Import via CSV file</h4>

    <p><b>CSV format:</b> Format your CSV the same way as the example below (with the first title row). Use the ID or email columns if you want to update a Subscriber instead of creating it.</p>

    <table class="table table-bordered table-condensed table-striped">
        <thead>
            <tr>
                <th>id</th>
                <th>email</th>
                <th>first_name</th>
                <th>last_name</th>
            </tr>
            <tbody>
                <tr>
                    <td></td>
                    <td>me@sendportal.io</td>
                    <td>Myself</td>
                    <td>Included</td>
                </tr>
            </tbody>
        </thead>
    </table>

    {!! Form::open(['route' => ['subscribers.import.store'], 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) !!}

    {!! Form::fileField('file', 'File') !!}

    {!! Form::submitButton('Upload') !!}

    {!! Form::close() !!}

@stop
