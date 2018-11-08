@extends('common.template')

@section('heading')
    Add configuration
@stop

@section('content')

    {!! Form::open(['method' => 'post', 'route' => 'providers.store']) !!}

    {!! Form::textField('name', 'Name') !!}
    {!! Form::selectField('type_id', 'Provider', $providerTypes) !!}

    <div id="provider-fields"></div>

    {!! Form::submitButton('Save') !!}
    {!! Form::close() !!}

@stop

@section('js')
    <script>

        var url = '{{ route('providers.ajax', 1) }}';

        $(function()
        {
            createFields(1);

            $('#id-field-type_id').on('change', function()
            {
                console.log(this.value)
                createFields(this.value);
            });
        });

        function createFields(providerTypeId)
        {
            url = url.substring(0, url.length - 1) + providerTypeId;

            $.get(url, function(result)
            {
                $('#provider-fields').html('');

                $.each(result, function(name, field)
                {
                    var string = '';

                    string += '<div class="form-group form-group-' + field;
                    string += '"><label for="id-field-' + field;
                    string += '" class="control-label col-sm-2">' + name;
                    string += '</label><div class="col-sm-10"><input id="id-field-' + field;
                    string += '" class="form-control" name="' + field;
                    string += '" type="text"></div></div>';

                    $('#provider-fields').append(string);
                })
            });
        }

    </script>
@endsection