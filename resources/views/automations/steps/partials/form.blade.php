{!! Form::textField('subject', 'Email Subject') !!}
{!! Form::textareaField('content', 'Content') !!}
{!! Form::selectField('template_id', 'Template', $templates) !!}
{!! Form::textField('delay', 'Delay') !!}
{!! Form::selectField('delay_type', 'Delay Period', \App\Models\AutomationStep::listFrequencies()) !!}

@push('css')
    <link href="{{ asset('css/summernote/summernote.css') }}" rel="stylesheet">
@endpush

@push('js')
    <script src="{{ asset('js/summernote.min.js') }}"></script>

    <script>
        $(function () {
            $('#id-field-content').summernote({
                minHeight: 200
            });
        });
    </script>
@endpush