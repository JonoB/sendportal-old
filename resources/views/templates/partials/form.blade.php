@section('css')
    <link rel="stylesheet" href="{{ asset('css/codemirror/codemirror.css') }}">
    <link rel="stylesheet" href="{{ asset('css/codemirror/themes/monokai.css') }}">
@endsection

{!! Form::textField('name', 'Template Name') !!}

<ul class="nav nav-pills">
    <li role="presentation" class="active"><a href="#">Edit</a></li>
    <li role="presentation"><a href="#">Preview</a></li>
</ul>

{!! Form::textareaField('content') !!}

{!! Form::submitButton() !!}

{!! Form::close() !!}

@section('js')
    <script src="{{ asset('js/codemirror/codemirror.js') }}"></script>
    <script src="{{ asset('js/codemirror/modes/xml.js') }}"></script>

    <script>
        $(document).ready(function() {
            var editor = CodeMirror.fromTextArea(document.getElementById("id-field-content"), {
                lineNumbers: true,
                //styleActiveLine: true,
                //matchBrackets: true,
                mode: 'xml',
                theme: 'monokai'
            });
        })
    </script>
@endsection
