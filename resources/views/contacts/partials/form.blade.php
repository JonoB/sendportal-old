{!! Form::textField('email', 'Email') !!}
{!! Form::textField('first_name', 'FirstName') !!}
{!! Form::textField('last_name', 'Last Name') !!}

@foreach($segments as $segment)
    <div class="checkbox">
        <label>
            <input name="segments[]" type="checkbox" value="{{ $segment->id }}" {{ in_array($segment->id, $selectedSegments) ? 'checked' : '' }}>{{ $segment->name }}
        </label>
    </div>
@endforeach

{!! Form::submitButton() !!}

{!! Form::close() !!}
