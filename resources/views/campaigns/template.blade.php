@extends('layouts.app')

@section('title', 'Campaign Template')

@section('heading')
    Campaign Template
@stop

@section('content')

    {!! Form::model($campaign, ['id' => 'form-template-selector', 'method' => 'put', 'route' => ['campaigns.template.update', $campaign->id]]) !!}

    <input type="hidden" id="field-template_id" name="template_id" value="{{ $campaign->template_id }}">

    <div class="row">
        @foreach($templates as $template)
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6 template-item">
                <div class="card">
                    <div class="card-header card-header-accent">
                        <div class="card-header-inner">
                            <div class="float-left">
                                <h4>{{ $template->name }}</h4>
                            </div>
                            <div class="float-right">
                                @if ($campaign->template_id == $template->id)
                                    <span class="label label-success">Selected</span>
                                @else
                                    <a href="#" class="btn btn-secondary btn-xs js-select-template" data-template_id="{{ $template->id }}">Select</a>
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

    <a href="{{ route('campaigns.edit', $campaign->id) }}" class="btn btn-link"><i class="fa fa-arrow-left"></i> Back</a>

    <button class="btn btn-primary" type="submit">Save and continue</button>

    {!! Form::close() !!}

@stop

@push('js')
    <script>
        $(function() {
            $('.js-select-template').click(function(e) {
                alert('what');
                e.preventDefault();
                $('#field-template_id').val($(this).data('template_id'));
                $('#form-template-selector').submit();
            });
        });
    </script>
@endpush