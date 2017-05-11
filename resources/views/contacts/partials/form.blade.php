{!! Form::textField('email', 'Email') !!}
{!! Form::textField('first_name', 'First Name') !!}
{!! Form::textField('last_name', 'Last Name') !!}
{!! Form::checkboxField('unsubscribed', 'Unsubscribed') !!}

@foreach($segments as $segment)
    <div class="checkbox">
        <label>
            <input name="segments[]" type="checkbox" value="{{ $segment->id }}" {{ in_array($segment->id, $selectedSegments) ? 'checked' : '' }}>{{ $segment->name }}
        </label>
    </div>
@endforeach

{!! Form::submitButton('Save Contact') !!}

{!! Form::close() !!}
