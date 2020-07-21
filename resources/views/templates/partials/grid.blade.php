<div class="row">
    @foreach($templates as $template)
        <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="card">
                <div class="card-header card-header-accent">
                    <div class="card-header-inner">
                        <div class="float-left">
                            <h4>{{ $template->name }}</h4>
                        </div>
                        <div class="float-right">
                            @if ( ! $template->is_in_use)
                                <form action="{{ route('templates.destroy', $template->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <a href="{{ route('templates.edit', $template->id) }}" class="btn btn-xs btn-secondary">Edit</a>
                                    <button type="submit" class="btn btn-xs btn-secondary">Delete</button>
                                </form>
                            @endif
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
                <div class="card-body">
                    @include('templates.partials.griditem')
                </div>
            </div>
        </div>
    @endforeach
</div>

{{ $templates->links() }}
