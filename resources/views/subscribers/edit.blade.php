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