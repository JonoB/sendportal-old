@section('css')
    <link rel="stylesheet" href="{{ asset('css/codemirror/codemirror.css') }}">
    <link rel="stylesheet" href="{{ asset('css/codemirror/themes/monokai.css') }}">
@endsection

{!! Form::textField('name', 'Template Name') !!}

<textarea id="code" name="code"></textarea>

{!! Form::submitButton() !!}

{!! Form::close() !!}

@section('js')
    <script src="{{ asset('js/codemirror/codemirror.js') }}"></script>
    <script src="{{ asset('js/codemirror/modes/xml.js') }}"></script>

    <script>
        $(document).ready(function() {
            var editor = CodeMirror.fromTextArea(document.getElementById("code"), {
                lineNumbers: true,
                //styleActiveLine: true,
                //matchBrackets: true,
                mode: 'xml',
                theme: 'monokai'
            });
        })
    </script>
@endsection
