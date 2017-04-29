@if (isset($errors) and count($errors) > 0)
    <div class="callout callout-danger">
        <?php $allErrors = $errors->getMessages(); ?>
        <ul>
            @foreach ($allErrors as $error)
                <li>{{ $error[0] }}</li>
            @endforeach
        </ul>
    </div>
@endif