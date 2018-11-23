@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.min.css">
@stop

{!! Form::textField('first_name', 'First Name') !!}
{!! Form::textField('last_name', 'Last Name') !!}
{!! Form::textField('email', 'Email') !!}

<div class="form-group form-group-subscribers">
    <label for="id-field-subscribers" class="control-label col-sm-2">Segments</label>
    <div class="col-sm-10">
        <select name="segments[]" id="id-field-subscribers" multiple="multiple">
            @foreach($segments as $segment)
                <option value="{{ $segment->id }}">{{ $segment->name }}</option>
            @endforeach
        </select>
    </div>
</div>

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js"></script>

    <script>
        $('select[name="segments[]"]').selectize({
            plugins: ['remove_button'],
            items: {!! isset($subscriber) ? $subscriber->segments->pluck('id')->toJson() : '[]' !!},
            create: function(input, callback) {
                $.ajax({
                    method: 'POST',
                    url: "{{ route('ajax.segments.store') }}",
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: {
                        name: input
                    }
                }).done(function (response) {
                    callback({
                        'value': response.data.id,
                        'text': response.data.name
                    });
                }).fail(function (err) {
                    console.log(err)
                });
            }
        });
    </script>
@stop
