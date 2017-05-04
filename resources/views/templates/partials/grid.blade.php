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

            @include('templates.partials.griditem')

        </div>
    @endforeach
</div>

{{ $templates->links() }}