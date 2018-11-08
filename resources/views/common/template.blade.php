<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Home') | {{ config('app.name', 'Sendportal') }}</title>
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

    <!-- Date Picker -->
    <link rel="stylesheet" href="{{ asset('css/datepicker3.css') }}">

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

@auth

    @include('common.header')

    @include('common.leftnav')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="content-wrapper-inner">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>
                    @yield('heading')
                </h1>

                @yield('breadcrumbs')
            </section>
            <section class="content">
                @include('common.messages')
                @yield('content')
            </section>
        </div>
        <!-- /.content-wrapper -->

    @elseguest

        @yield('content')

    @endauth
    </div>
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

<!-- datepicker -->
<script src="{{ asset('js/bootstrap-datepicker.js') }}"></script>

<!-- AdminLTE App -->
<script src="{{ asset('js/admin-lte.js') }}"></script>

@yield('js')

</body>
</html>
