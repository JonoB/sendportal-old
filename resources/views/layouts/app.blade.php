<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>{{ config('app.name') }}</title>

    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/fa.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/datepicker.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') . '?m=' . filemtime(public_path('css/app.css')) }}" rel="stylesheet">

</head>
<body>

<div class="container-fluid">
    <div class="row">

        @auth()
            @include('layouts.partials.sidebar')
        @endauth()

        @include('layouts.main')
    </div>
</div>

<script src="{{ asset('js/jquery-3.3.1.js') }}"></script>
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('js/datepicker.min.js') }}"></script>
<script src="{{ asset('js/select2.min.js') }}"></script>

<script>
  $('.sidebar-toggle').click(function (e) {
    e.preventDefault();
    toggleElements();
  });

  function toggleElements() {
    $('.sidebar').toggleClass('d-none');
  }
</script>

@stack('js')

</body>
</html>
