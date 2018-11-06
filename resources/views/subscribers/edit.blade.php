@extends('common.template')

@section('heading')
    Edit Subscriber : {{ $subscriber->name }}
@stop

@section('content')

    {!! Form::model($subscriber, ['method' => 'put', 'class' => 'form-horizontal', 'route' => ['subscribers.update', $subscriber->id]]) !!}

    @include('subscribers.partials.form')

    <label for="">Tags</label>

    @foreach($tags as $tag)

        {!! Form::checkboxField('tags[]', $tag->name, $tag->id, ['checked' => in_array($tag->id, $selectedTags)]) !!}

    @endforeach

    <br>

    {!! Form::submitButton('Save') !!}

    {!! Form::close() !!}

@stop