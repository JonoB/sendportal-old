@extends('common.template')

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.min.css">
@stop

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

    <div class="form-group form-group-subscribers">
        <label for="id-field-subscribers" class="control-label col-sm-2">Segments</label>
        <div class="col-sm-10">
            {!! Form::select('segments[]', $segments, null, ['multiple' => true]) !!}
        </div>
    </div>

    {!! Form::submitButton('Upload') !!}

    {!! Form::close() !!}

@stop

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js"></script>

    <script>
        $('select[name="segments[]"]').selectize({
            plugins: ['remove_button']
        });
    </script>
@stop