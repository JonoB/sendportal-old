{!! Form::textField('email', 'Email') !!}
{!! Form::textField('first_name', 'First Name') !!}
{!! Form::textField('last_name', 'Last Name') !!}
{!! Form::checkboxField('unsubscribed', 'Unsubscribed') !!}

@foreach($tags as $tag)
    <div class="checkbox">
        <label>
            <input name="tags[]" type="checkbox" value="{{ $tag->id }}" {{ in_array($tag->id, $selectedTags) ? 'checked' : '' }}>{{ $tag->name }}
        </label>
    </div>
@endforeach

<div class="well">
    <h4>Custom Fields</h4>

    @if ( ! empty($subscriber->meta))
        @foreach (json_decode($subscriber->meta) as $id => $metaField)
            @include('lists.subscribers.partials.meta_field', compact('$metaField'))
        @endforeach
    @endif

    <button class="btn btn-default btn-sm">Add Custom Field</button>
</div>


{!! Form::submitButton('Save') !!}
