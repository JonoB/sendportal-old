@extends('common.template')

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.min.css">
@stop

@section('heading')
    Edit Segment : {{ $segment->name }}
@stop

@section('content')

    {!! Form::model($segment, ['method' => 'put', 'class' => 'form-horizontal', 'route' => ['segments.update', $segment->id]]) !!}

    @include('segments.partials.form')

    <div class="form-group form-group-subscribers">
        <label for="id-field-subscribers" class="control-label col-sm-2">Subscribers</label>
        <div class="col-sm-10">
            <select name="subscribers[]" id="id-field-subscribers" multiple="multiple">
                @foreach($subscribers as $subscriber)
                    @if (in_array($subscriber->id, $segment->subscribers->pluck('id')->toArray()))
                        @php
                            $existing = $segment->subscribers->first(function ($existing) use ($subscriber) {
                                return $existing->id === $subscriber->id;
                            });
                        @endphp
                        <option value="{{ $subscriber->id }}" {{ optional($existing->pivot)->unsubscribed_at ? 'disabled' : null }}>{{ $subscriber->full_name }}</option>
                    @else
                        <option value="{{ $subscriber->id }}">{{ $subscriber->full_name }}</option>
                    @endif
                @endforeach
            </select>
        </div>
    </div>

    {!! Form::submitButton('Save') !!}

    {!! Form::close() !!}
@stop

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js"></script>

    <script>
        $('select[name="subscribers[]"]').selectize({
            items: {!! $segment->subscribers->pluck('id')->toJson() !!},
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