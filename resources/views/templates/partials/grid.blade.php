<div class="row">
    @foreach($templates as $template)
        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-6 template-item">
            <div class="pull-left">
                <h4>{{ $template->name }}</h4>
            </div>
            <div class="pull-right">
                <a href="{{ route('templates.edit', $template->id) }}">Edit</a>

                @if ( ! $template->is_in_use)
                    <form action="{{ route('templates.destroy', $template->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Delete</button>
                    </form>
                @endif
            </div>
            <div class="clearfix"></div>

            @include('templates.partials.griditem')

        </div>
    @endforeach
</div>

{{ $templates->links() }}
