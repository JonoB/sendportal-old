@section('css')
    <link rel="stylesheet" href="{{ asset('css/codemirror/codemirror.css') }}">
    <link rel="stylesheet" href="{{ asset('css/codemirror/themes/monokai.css') }}">
@endsection

<div class="row template-editor-container">
    <div class="col-sm-6">

        {!! Form::textareaField('content') !!}

    </div>

    <div class="col-sm-6">
        <div style="border: 1px solid #ddd; height: 600px">
            <iframe id="js-template-iframe" class="embed-responsive-item" frameborder="0" style="height: 100%; width: 100%"></iframe>
        </div>
    </div>
</div>

@section('js')
    <script src="{{ asset('js/codemirror/codemirror.js') }}"></script>
    <script src="{{ asset('js/codemirror/modes/xml.js') }}"></script>

    <script>
        $(document).ready(function() {
            editor = CodeMirror.fromTextArea(document.getElementById('id-field-content'), {
                lineNumbers: true,
                mode: 'xml',
                theme: 'monokai'

            });

            editor.on('change', function(editor, change) {
                copyEditorToIframe(editor.getValue());
            });

            copyEditorToIframe(editor.getValue());
        });

        function copyEditorToIframe(html) {
            var iframe = document.getElementById('js-template-iframe');
            var iframedoc = iframe.contentDocument || iframe.contentWindow.document;

            iframedoc.body.innerHTML = html;
        }
    </script>
@endsection
