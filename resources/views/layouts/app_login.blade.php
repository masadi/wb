<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') | {{ config('app.name', 'Laravel') }}</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{asset('vendor/img/logo.png')}}">
    <link rel="stylesheet" href="{{asset('vendor/fontawesome-free/css/all.min.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/icheck-bootstrap/3.0.1/icheck-bootstrap.min.css ">
    @yield('css')
    <link rel="stylesheet" href="{{asset('vendor/adminlte/dist/css/adminlte.min.css')}}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <link rel='stylesheet' type="text/css" href='https://fonts.googleapis.com/css?family=Titillium+Web:400,600,700,300'>
    <!--link rel="shortcut icon" href="{{asset('favicons/favicon.ico')}}" /-->
</head>

<body class="hold-transition login-page">
    @yield('content')
    <script src="{{asset('vendor/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('vendor/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>
    @yield('js_file')
    <script src="{{asset('vendor/adminlte/dist/js/adminlte.min.js')}}"></script>
    <script>
        $(document).bind("ajaxSend", function() {
			$("#spinner").show();
		}).bind("ajaxStop", function() {
			$("#spinner").hide();
		}).bind("ajaxError", function() {
			$("#spinner").hide();
		});
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    @yield('js')
</body>

</html>