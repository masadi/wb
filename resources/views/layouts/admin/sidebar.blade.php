<?php
$user = auth()->user();
?>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{url('/')}}" class="brand-link">
        <!--router-link tag="a" to="/beranda" class="brand-link"-->
        <img src="/images/AdminLTELogo.png" alt="{{ config('app.name', 'Laravel') }}" class="brand-image img-circle elevation-3"
            style="opacity: .8">
        <span class="brand-text font-weight-light">{{ config('app.name', 'Laravel') }}</span>
        <!--/router-link-->
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <router-link tag="a" to="/beranda" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Beranda</p>
                    </router-link>
                </li>
                @if($user->isAbleTo('news-create'))
                <!--li class="nav-item">
                    <router-link tag="a" to="/galeri" class="nav-link">
                        <i class="nav-icon fas fa-photo-video"></i>
                        <p>Galeri</p>
                    </router-link>
                </li-->
                @endif
                <li class="nav-item">
                    <router-link tag="a" to="/sekolah" class="nav-link">
                        <i class="nav-icon fas fa-house-user"></i>
                        <p>Sekolah</p>
                    </router-link>
                </li>
                @if($user->isAbleTo('referensi-create'))
                <li class="nav-item">
                    <router-link tag="a" to="/tanah" class="nav-link">
                        <i class="nav-icon fas fa-road"></i>
                        <p>Tanah</p>
                    </router-link>
                </li>
                <li class="nav-item">
                    <router-link tag="a" to="/bangunan" class="nav-link">
                        <i class="nav-icon fas fa-building"></i>
                        <p>Bangunan</p>
                    </router-link>
                </li>
                <li class="nav-item">
                    <router-link tag="a" to="/ruang" class="nav-link">
                        <i class="nav-icon fas fa-home"></i>
                        <p>Ruang</p>
                    </router-link>
                </li>
                <li class="nav-item">
                    <router-link tag="a" to="/alat" class="nav-link">
                        <i class="nav-icon fas fa-cogs"></i>
                        <p>Alat</p>
                    </router-link>
                </li>
                <li class="nav-item">
                    <router-link tag="a" to="/angkutan" class="nav-link">
                        <i class="nav-icon fas fa-car"></i>
                        <p>Angkutan</p>
                    </router-link>
                </li>
                <li class="nav-item">
                    <router-link tag="a" to="/buku" class="nav-link">
                        <i class="nav-icon fas fa-book"></i>
                        <p>Buku</p>
                    </router-link>
                </li>
                @endif
                @if($user->isAbleTo('users-create'))
                <li class="nav-item">
                    <router-link tag="a" to="/pengguna" class="nav-link">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Pengguna</p>
                    </router-link>
                </li>
                @endif
                <li class="nav-item">
                    <router-link tag="a" to="/profil" class="nav-link">
                        <i class="nav-icon fas fa-user"></i>
                        <p>Profil Pengguna</p>
                    </router-link>
                </li>
                <li class="nav-item">
                    <router-link tag="a" to="/unduhan" class="nav-link">
                        <i class="nav-icon fas fa-download"></i>
                        <p>Pusat Unduhan</p>
                    </router-link>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-danger" href="javascript:{}"
                        onclick="document.getElementById('logout-form-sidebar').submit();">
                        <i class="nav-icon fas fa-sign-out-alt"></i> Keluar Aplikasi
                    </a>
                    <form id="logout-form-sidebar" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
                <!-- New Sidebar Item -->
            </ul>
        </nav>
        <!-- /.sidebar-menu -->

    </div>
    <!-- /.sidebar -->
</aside>