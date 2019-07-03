<div class="row">
    <div class="col-lg-8 offset-lg-2">
        @if ($errors->any())
            <div class="alert alert-danger">
                <p>Please correct the following errors:</p>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
</div>
