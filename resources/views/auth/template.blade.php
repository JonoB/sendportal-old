<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title')</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">

    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('css/AdminLTE.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/skin-blue.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">

@yield('css')

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="hold-transition skin-blue">
<div class="wrapper">

    @yield('content')

</div>
<!-- ./wrapper -->


@auth
    <footer class="main-footer">
        @yield('footer')
    </footer>
@endauth

<!-- jQuery 2.2.3 -->
<script src="{{ asset('js/jquery-2.2.3.min.js') }}"></script>

<!-- jQuery UI 1.11.4 -->
<!-- <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>--»
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  //$.widget.bridge('uibutton', $.ui.button);
</script>

<!-- Bootstrap 3.3.6 -->
<script src=" {{ asset('js/bootstrap.min.js') }} "></script>

@yield('js')

</body>
</html>
