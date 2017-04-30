@extends('common.template')

@section('heading')
    Email Templates
@endsection

@section('content')
    <div class="actions-container">
        <a class="btn btn-primary btn-flat pull-right" href="{{ route('templates.create') }}">Create Template</a>
        <div class="clearfix"></div>
    </div>

    <div class="row">
        @foreach($templates as $template)
            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-6 template-item">
                <div class="pull-left">
                    <h4>{{ $template->name }}</h4>
                </div>
                <div class="pull-right">
                    <a href="{{ route('templates.edit', $template->id) }}">Edit</a>
                </div>
                <div class="clearfix"></div>
                <div class="template-panel">
                    <a href="{{ route('templates.edit', $template->id) }}" style="display:block">
                    <div class="template-preview">
                        <iframe width="600" height="600" scrolling="no" frameborder="0" srcdoc="{{ $template->content }}"></iframe>
                    </div>
                    </a>

                </div>
            </div>
        @endforeach
    </div>

    {{ $templates->links() }}


@endsection
