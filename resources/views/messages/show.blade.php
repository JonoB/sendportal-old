@extends('layouts.app')

@section('title', 'Message')

@section('heading', 'Draft Message')

@section('content')

    <div class="card mb-5">
        <div class="card-header card-header-accent">
            <div class="card-header-inner">
                <div class="float-right">
                    @if ($message->sent_at)
                        Sent {{ $message->sent_at->diffForHumans() }}
                    @else
                        <form action="{{ route('messages.send') }}" method="post">
                            @csrf
                            <input type="hidden" name="id" value="{{ $message->id }}">
                            <button type="submit" class="btn btn-sm btn-primary">Send now</button>
                        </form>
                    @endif
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
        <div class="card-body">
            <table>
                <tbody>
                <tr>
                    <td width="75px"><b>To:</b></td>
                    <td>{{ $message->recipient_email }}</td>
                </tr>
                <tr>
                    <td><b>Subject:</b></td>
                    <td>{{ $message->subject }}</td>
                </tr>
                <tr>
                    <td><b>From:</b></td>
                    <td>{{ $message->from_name }} &lt;{{ $message->from_email }}</td>
                </tr>
                </tbody>
            </table>

            <hr>

            <iframe id="iframe-content" width="100%" height="100%" scrolling="no" frameborder="0" srcdoc="{{ $content }}"></iframe>
        </div>
    </div>

@endsection

@push('js')
    <script>
        $(function() {
            newheight = document.getElementById('iframe-content').contentWindow.document .body.scrollHeight;
            document.getElementById('iframe-content').height = (newheight) + "px";
        });
    </script>
@endpush


