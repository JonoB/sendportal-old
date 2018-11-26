@section('css')
    <link rel="stylesheet" href="{{ asset('css/codemirror/codemirror.css') }}">
    <link rel="stylesheet" href="{{ asset('css/codemirror/themes/monokai.css') }}">
    <link rel="stylesheet" href="{{ asset('css/summernote/summernote.css') }}">
@endsection

<div class="template-editor-container">

        <textarea id="id-field-content" class="form-control" name="content" cols="50" rows="10">{{ $email->content ?? '' }}</textarea>

        {!! Form::hidden('template_content', $email->template->content) !!}

        <div style="border: 1px solid #ddd; height: 600px">
            <iframe id="js-template-iframe" class="embed-responsive-item" frameborder="0"
                    style="height: 100%; width: 100%"></iframe>
        </div>
</div>

@section('js')
    <script src="{{ asset('js/codemirror/codemirror.js') }}"></script>
    <script src="{{ asset('js/codemirror/modes/xml.js') }}"></script>
    <script src="{{ asset('js/summernote.min.js') }}"></script>

    <script>
        $(document).ready(function () {
            var el = $('#id-field-content');

            el.summernote({
                minHeight: 100,
                callbacks: {
                    onChange: function(contents) {
                        copyEditorToIframe(contents);
                    }
                }
            });

            copyEditorToIframe(el.summernote('code'));
        });

        function copyEditorToIframe(html) {
            const iframe = document.getElementById('js-template-iframe');
            const iframedoc = iframe.contentDocument || iframe.contentWindow.document;
            const templateContent = document.querySelector('input[name="template_content"]').value;

            // NOTE(david): the @{{ content }} is so that blade doesn't interpret it as a variable
            iframedoc.body.innerHTML = templateContent.replace('@{{content}}', html);
        }
    </script>
@endsection
