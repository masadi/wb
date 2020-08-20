<nav class="main-header navbar navbar-expand navbar-white navbar-light stycky-top">
    
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item h4 mb-0">
            {{auth()->user()->name}} {!!(config('app.tahun_pendataan') ? ' &raquo; Tahun Pendataan '.config('app.tahun_pendataan') : NULL)!!}
        </li>
    </ul>

    <ul class="navbar-nav ml-auto">

        <!-- Control Sidebar -->
        <li class="nav-item">
            <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#">
                <i  class="fas fa-th-large"></i>
            </a>
        </li>

    </ul>

</nav>
