<div class="container">
    <h1>Subscribe</h1>
    <p>Enable mailings in the future.</p>

    {!! Form::open(['route' => ['subscriptions.update', $subscriber->id], 'method' => 'PUT']) !!}

    {!! Form::hidden('is_unsubscribed', 0) !!}

    {!! Form::submitButton('Subscribe') !!}

    {!! Form::close() !!}

</div>
