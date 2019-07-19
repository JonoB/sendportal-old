@push('css')
    <link rel="stylesheet" href="{{ asset('css/codemirror/codemirror.css') }}">
    <link rel="stylesheet" href="{{ asset('css/codemirror/themes/monokai.css') }}">
@endpush

<div class="template-editor-container">
    <div class="form-group">
        <textarea id="id-field-content" class="form-control" name="content" cols="50" rows="20">{{ $template->content ?? '' }}</textarea>
    </div>
</div>

@push('js')
    <script src="{{ asset('js/codemirror/codemirror.js') }}"></script>
    <script src="{{ asset('js/codemirror/modes/xml.js') }}"></script>

    <script>
        $(document).ready(function () {
            CodeMirror.fromTextArea(document.getElementById('id-field-content'), {
                lineNumbers: true,
                mode: 'xml',
                theme: 'monokai'
            });
        });
    </script>
@endpush
