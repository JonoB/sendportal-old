@extends('common.template')

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.min.css">
@stop

@section('heading')
    Edit Subscriber : {{ $subscriber->full_name }}
@stop

@section('content')

    {!! Form::model($subscriber, ['method' => 'put', 'class' => 'form-horizontal', 'route' => ['subscribers.update', $subscriber->id]]) !!}

    @include('subscribers.partials.form')

    <div class="form-group form-group-tags">
        <label for="id-field-tags" class="control-label col-sm-2">Tags</label>
        <div class="col-sm-10">
            <select name="tags[]" id="id-field-tags" multiple="multiple">
                @foreach($tags as $tag)
                    <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="form-group form-group-segments">
        <label for="id-field-segments" class="control-label col-sm-2">Segments</label>
        <div class="col-sm-10">
            <select name="segments[]" id="id-field-segments" multiple="multiple">
                @foreach($segments as $segment)
                    @if (in_array($segment->id, $subscriber->segments->pluck('id')->toArray()))
                        @php
                            $existing = $subscriber->segments->first(function ($existing) use ($segment) {
                                return $existing->id === $segment->id;
                            });
                        @endphp
                        <option value="{{ $segment->id }}" {{ optional($existing->pivot)->unsubscribed_at ? 'disabled' : null }}>{{ $segment->name }}</option>
                    @else
                        <option value="{{ $segment->id }}">{{ $segment->name }}</option>
                    @endif
                @endforeach
            </select>
        </div>
    </div>

    <br>

    {!! Form::submitButton('Save') !!}

    {!! Form::close() !!}

@stop

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js"></script>

    <script>
        $('select[name="tags[]"]').selectize({
            items: {!! json_encode($selectedTags) !!}
        });

        $('select[name="segments[]"]').selectize({
            items: {!! $subscriber->segments->pluck('id')->toJson() !!},
            render: {
                item: function (value, escape) {
                    var out = '';

                    if (value.disabled) {
                        out += '<del>' + escape(value.text) + '</del>';
                    } else {
                        out += escape(value.text);
                    }

                    out = '<div class="item" data-value="' + escape(value) + '">' + out + '</div>';

                    return out;
                }
            }
        });
    </script>
@stop