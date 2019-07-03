@extends('layouts.app')

@section('title', 'Campaign Template')

@section('heading')
    Campaign Template
@stop

@section('content')

    {!! Form::model($campaign, ['id' => 'form-template-selector', 'method' => 'put', 'route' => ['campaigns.template.update', $campaign->id]]) !!}

    <input type="hidden" id="field-template_id" name="template_id" value="{{ $campaign->email->template_id }}">

    <div class="row">
        @foreach($templates as $template)
            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-6 template-item">
                <div class="pull-left">
                    <h4>{{ $template->name }}</h4>
                </div>
                <div class="pull-right">
                    @if ($campaign->email->template_id == $template->id)
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

    <a href="{{ route('campaigns.edit', $campaign->id) }}" class="btn btn-link"><i class="fa fa-arrow-left"></i> Back</a>

    <button class="btn btn-primary" type="submit">Save and continue</button>

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