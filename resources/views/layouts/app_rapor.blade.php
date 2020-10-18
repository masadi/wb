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
    @yield('css')
    <link rel="stylesheet" href="{{asset('vendor/adminlte/dist/css/adminlte.min.css')}}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <link rel='stylesheet' type="text/css" href='https://fonts.googleapis.com/css?family=Titillium+Web:400,600,700,300'>
    <!--link rel="shortcut icon" href="{{asset('favicons/favicon.ico')}}" /-->
    <style>
      .bg-custom {
            background: #206d66 !important;
        }
    </style>
</head>
<?php
$route = Route::current();
$laman = $route->parameters['query'];
$active_rapor_mutu = '';
$aktif_rapor_mutu_sekolah = '';
$aktif_rapor_mutu_verifikasi = '';
$aktif_rapor_mutu_afirmasi = '';
if($laman == 'rapor-mutu-sekolah'){
    $active_rapor_mutu = ' menu-open';
    $aktif_rapor_mutu_sekolah = ' active';
}
if($laman == 'rapor-mutu-verifikasi'){
    $active_rapor_mutu = ' menu-open';
    $aktif_rapor_mutu_verifikasi = ' active';
}
if($laman == 'rapor-mutu-afirmasi'){
    $active_rapor_mutu = ' menu-open';
    $aktif_rapor_mutu_afirmasi = ' active';
}

$active_progres_data = '';
$aktif_instrumen = '';
$aktif_rapor_mutu = '';
$aktif_pakta_integritas = '';
$aktif_verval = '';
$aktif_verifikasi = '';
$aktif_pengesahan = '';
if($laman == 'progres-data' || $laman == 'progres'){
    $active_progres_data = ' menu-open';
    $aktif_instrumen = ' active';
}
if($laman == 'hitung-rapor-mutu'){
  $active_progres_data = ' menu-open';
  $aktif_rapor_mutu = ' active';
}
if($laman == 'cetak-pakta'){
  $active_progres_data = ' menu-open';
  $aktif_pakta_integritas = ' active';
}
if($laman == 'verval'){
  $active_progres_data = ' menu-open';
  $aktif_verval = ' active';
}
if($laman == 'verifikasi'){
  $active_progres_data = ' menu-open';
  $aktif_verifikasi = ' active';
}
if($laman == 'pengesahan'){
  $active_progres_data = ' menu-open';
  $aktif_pengesahan = ' active';
}
$aktif_login = '';
if($laman == 'login'){
  $aktif_login = ' active';
}
?>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <nav class="main-header navbar navbar-expand navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
              <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
              </li>
            </ul>
        
            </nav>
          <aside class="main-sidebar sidebar-light-danger elevation-4 bg-custom text-white">
            <!-- Brand Logo -->
            <a href="{{url('/')}}" class="brand-link">
              <img src="/images/AdminLTELogo.png" alt="APM SMK" class="brand-image img-circle elevation-3"
                   style="opacity: .8">
              <span class="brand-text font-weight-light text-white"><strong> APM SMK </strong></span>
            </a>
        
            <!-- Sidebar -->
            <div class="sidebar">
              <!-- Sidebar user panel (optional) -->
              <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                MENU
              </div>
        
              <!-- Sidebar Menu -->
              <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent text-sm" data-widget="treeview" role="menu" data-accordion="false">
                      <li class="nav-item has-treeview {{$active_rapor_mutu}}">
                        <a href="#" class="nav-link text-white{{$aktif_rapor_mutu_sekolah}}{{$aktif_rapor_mutu_verifikasi}}{{$aktif_rapor_mutu_afirmasi}}">
                          <i class="nav-icon fas fa-th"></i>
                          <p>
                            Rapor Mutu
                            <i class="fas fa-angle-left right"></i>
                          </p>
                        </a>
                        <ul class="nav nav-treeview">
                          <li class="nav-item">
                            <a href="{{route('page', ['query' => 'rapor-mutu-sekolah'])}}" class="nav-link text-white{{$aktif_rapor_mutu_sekolah}}">
                              <i class="fas fa-check nav-icon"></i>
                              <p>Sekolah</p>
                            </a>
                          </li>
                          <li class="nav-item">
                            <a href="{{route('page', ['query' => 'rapor-mutu-verifikasi'])}}" class="nav-link text-white{{$aktif_rapor_mutu_verifikasi}}">
                              <i class="fas fa-check nav-icon"></i>
                              <p>Verifikasi</p>
                            </a>
                          </li>
                          <li class="nav-item">
                            <a href="{{route('page', ['query' => 'rapor-mutu-afirmasi'])}}" class="nav-link text-white{{$aktif_rapor_mutu_afirmasi}}">
                              <i class="fas fa-check nav-icon"></i>
                              <p>Afirmasi</p>
                            </a>
                          </li>
                        </ul>
                      </li>
                      @auth
                      <li class="nav-item has-treeview {{$active_progres_data}}">
                        <a href="#" class="nav-link text-white{{$aktif_instrumen}}{{$aktif_rapor_mutu}}{{$aktif_pakta_integritas}}{{$aktif_verval}}{{$aktif_verifikasi}}{{$aktif_pengesahan}}">
                          <i class="nav-icon fas fa-sync"></i>
                          <p>
                            Progres Data
                            <i class="fas fa-angle-left right"></i>
                          </p>
                        </a>
                        <ul class="nav nav-treeview">
                          <li class="nav-item">
                            <a href="{{route('page', ['query' => 'progres-data'])}}" class="nav-link text-white{{$aktif_instrumen}}">
                              <i class="fas fa-check nav-icon"></i>
                              <p>Pengisian Instrumen</p>
                            </a>
                          </li>
                          <li class="nav-item">
                            <a href="{{route('page', ['query' => 'hitung-rapor-mutu'])}}" class="nav-link text-white{{$aktif_rapor_mutu}}">
                              <i class="fas fa-check nav-icon"></i>
                              <p>Hitung Rapor Mutu</p>
                            </a>
                          </li>
                          <li class="nav-item">
                            <a href="{{route('page', ['query' => 'cetak-pakta'])}}" class="nav-link text-white{{$aktif_pakta_integritas}}">
                              <i class="fas fa-check nav-icon"></i>
                              <p>Cetak Pakta Integritas</p>
                            </a>
                          </li>
                          <li class="nav-item">
                            <a href="{{route('page', ['query' => 'verval'])}}" class="nav-link text-white{{$aktif_verval}}">
                              <i class="fas fa-check nav-icon"></i>
                              <p>Verval Verifikator</p>
                            </a>
                          </li>
                          <li class="nav-item">
                            <a href="{{route('page', ['query' => 'verifikasi'])}}" class="nav-link text-white{{$aktif_verifikasi}}">
                              <i class="fas fa-check nav-icon"></i>
                              <p>Verval Pusat</p>
                            </a>
                          </li>
                          <li class="nav-item">
                            <a href="{{route('page', ['query' => 'pengesahan'])}}" class="nav-link text-white{{$aktif_pengesahan}}">
                              <i class="fas fa-check nav-icon"></i>
                              <p>Pengesahan Pusat</p>
                            </a>
                          </li>
                        </ul>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                          <i class="nav-icon fas fa-sign-out-alt"></i>
                          <p>Keluar Aplikasi</p>
                        </a>
                      </li>
                      <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                          @csrf
                      </form>
                      @else
                      <li class="nav-item">
                        <a href="{{route('page', ['query' => 'login'])}}" class="nav-link text-white{{$aktif_login}}">
                          <i class="nav-icon fas fa-sign-in-alt"></i>
                          <p>Masuk Aplikasi</p>
                        </a>
                      </li>
                      @endauth
                      
                </ul>
              </nav>
            </div>
          </aside>
        <?php
        /*$class = 'container-fluid';
        $params = request()->route()->parameters();
        if($params){
            if($params['query'] != 'home'){
                $class = 'container';
            }
        }*/
        $class = 'container';
        ?>
        <div class="content-wrapper ">
            <div class="content-header">
                <div class="container-fluid">
                </div>
            </div>
            <div class="content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>
        </div>
        <footer class="main-footer navbar-custom">
            Copyright &copy; 2020 <a href="http://psmk.kemdikbud.go.id/" target="_blank">DIREKTORAT SEKOLAH
                MENENGAH KEJURUAN</a>.
        </footer>
    </div>
    <div id="spinner"
        style="position:fixed; top: 50%; left: 50%; margin-left: -50px; margin-top: -50px;z-index: 999999;display: none;">
        <img src="{{asset('vendor/img/ajax-loader.gif')}}" />
    </div>
    <div id="modal_content" class="modal"></div>
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