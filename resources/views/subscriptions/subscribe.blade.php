<div class="container">
    <h1>Subscribe</h1>
    <p>Enable mailings in the future</p>

    {!! Form::open(['route' => ['subscriptions.update']]) !!}
    {!! Form::hidden('contact_id', $contactId) !!}
    {!! Form::hidden('unsubscribed', 0) !!}
    {!! Form::submitButton('Subscribe') !!}
    {!! Form::close() !!}

</div>
