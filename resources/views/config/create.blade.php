@extends('common.template')

@section('heading')
    Add configuration
@stop

@section('content')

    {!! Form::open(['method' => 'post', 'route' => 'config.store']) !!}

    {!! Form::selectField('type_id', 'Provider', $configTypes) !!}

    <div id="config-fields"></div>

    {!! Form::submitButton('Save') !!}
    {!! Form::close() !!}

    <script>

        var url = '{{ route('config.ajax', 1) }}';

        $(function()
        {
            createFields(1);

            $('#id-field-type_id').on('change', function()
            {
                createFields(this.value);
            });
        });

        function createFields(configTypeId)
        {
            url = url.substring(0, url.length - 1) + configTypeId;

            $.get(url, function(result)
            {
                $('#config-fields').html('');

                $.each(result, function(name, field)
                {
                    var string = '';

                    string += '<div class="form-group form-group-' + field;
                    string += '"><label for="id-field-' + field;
                    string += '" class="control-label col-sm-2">' + name;
                    string += '</label><div class="col-sm-10"><input id="id-field-' + field;
                    string += '" class="form-control" name="' + field;
                    string += '" type="text"></div></div>';

                    $('#config-fields').append(string);
                })
            });
        }

    </script>

@stop
