<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'AdminLTE') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        nav ul li ul:not(.dropdown-menu) {padding-left: 30px !important;}
    </style>
    <script>
        window.user = {!! json_encode([
            'user_id' => $user->user_id,
            'sekolah_id' => $user->sekolah_id,
            'name' => $user->name,
            'email' => $user->email,
            'username' => $user->username,
        ]) !!};
    </script>
</head>
<body class="hold-transition sidebar-mini dark text-sm">

    <!-- wrapper -->
    <div class="wrapper" id="pmp_smk">
        @yield('content')
    </div>
    <!-- ./wrapper -->

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

</body>

</html>
