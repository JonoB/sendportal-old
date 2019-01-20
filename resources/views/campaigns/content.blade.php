@extends('common.template')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/summernote/summernote.css') }}">
@endsection

@section('title', 'Campaign Content')

@section('heading', 'Campaign Content')

@section('content')
    <div class="row template-editor-container">
        <div class="col-sm-6">
            {!! Form::model($campaign, array('method' => 'put', 'route' => array('campaigns.content.update', $campaign->id))) !!}

            <div class="form-group">
                <textarea id="id-field-content" class="form-control" name="content" cols="50" rows="10">{{ $campaign->content ?? '' }}</textarea>
            </div>

            <button class="btn btn-primary" type="submit">Save and continue</button>

            {!! Form::close() !!}
        </div>

        <div class="col-sm-6">
            <div style="border: 1px solid #ddd; height: 600px">
                <iframe id="js-template-iframe" class="embed-responsive-item" frameborder="0"
                        style="height: 100%; width: 100%"></iframe>
            </div>
        </div>

    </div>
@endsection

@section('js')
    <script src="{{ asset('js/summernote.min.js') }}"></script>

    <script>
        $(document).ready(function () {
            var el = $('#id-field-content');

            el.summernote({
                minHeight: 555,
                callbacks: {
                    onChange: function(contents) {
                        copyEditorToIframe(contents);
                    }
                }
            });

            copyEditorToIframe(el.summernote('code'));
        });

        function copyEditorToIframe(html) {
            console.log(html);

            const iframe = document.getElementById('js-template-iframe');
            const iframedoc = iframe.contentDocument || iframe.contentWindow.document;

            // NOTE(david): the @{{ content }} is so that blade doesn't interpret it as a variable
            iframedoc.body.innerHTML = html;
        }
    </script>
@endsection
