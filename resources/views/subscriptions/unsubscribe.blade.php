<div class="container">
    <h1>Unsubscribe</h1>
    <p>Remove yourself from all future mailings.</p>

    {!! Form::open(['route' => ['subscriptions.update', $subscriber->id], 'method' => 'PUT']) !!}

    {!! Form::hidden('is_unsubscribed', 1) !!}

    {!! Form::submitButton('Unsubscribe') !!}

    {!! Form::close() !!}

</div>
