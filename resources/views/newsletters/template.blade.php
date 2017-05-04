@extends('common.template')

@section('heading')
    Newsletter Template
@stop

@section('content')

    {!! Form::model($newsletter, ['id' => 'form-template-selector', 'method' => 'put', 'route' => ['newsletters.template.update', $newsletter->id]]) !!}

    <input type="hidden" id="field-template_id" name="template_id" value="{{ $newsletter->template_id }}">

    <div class="row">
        @foreach($templates as $template)
            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-6 template-item">
                <div class="pull-left">
                    <h4>{{ $template->name }}</h4>
                </div>
                <div class="pull-right">
                    @if ($newsletter->template_id == $template->id)
                        <span class="label label-success">Selected</span>
                    @else
                        <a href="#" class="btn btn-default btn-xs js-select-template" data-template_id="{{ $template->id }}">Select</a>
                    @endif
                </div>
                <div class="clearfix"></div>

                @include('templates.partials.griditem')

            </div>
        @endforeach
    </div>

    {{ $templates->links() }}

    <a href="{{ route('newsletters.edit', $newsletter->id) }}" class="btn btn-default">Back</a>
    {!! Form::close() !!}

@stop

@section('js')
    <script>
        $(document).ready(function() {

            $('.js-select-template').click(function(e) {
                e.preventDefault();
                $('#field-template_id').val($(this).data('template_id'));
                $('#form-template-selector').submit();
            });
        });
    </script>
@endsection