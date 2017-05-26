{!! Form::textField('email', 'Email') !!}
{!! Form::textField('first_name', 'First Name') !!}
{!! Form::textField('last_name', 'Last Name') !!}
{!! Form::checkboxField('unsubscribed', 'Unsubscribed') !!}
{!! Form::selectField('subscriber_list_id', 'List', $subscriberLists) !!}

@foreach($tags as $tag)
    <div class="checkbox">
        <label>
            <input name="tags[]" type="checkbox" value="{{ $tag->id }}" {{ in_array($tag->id, $selectedTags) ? 'checked' : '' }}>{{ $tag->name }}
        </label>
    </div>
@endforeach

{!! Form::submitButton('Save') !!}

{!! Form::close() !!}
