<?php
$user = auth()->user();
?>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!--a href="{{url('/')}}" class="brand-link"-->
    <router-link tag="a" to="/beranda" class="brand-link">
        <img src="/images/AdminLTELogo.png" alt="APM SMK" class="brand-image img-circle elevation-3"
            style="opacity: .8">
        <span class="brand-text font-weight-light">APM SMK</span>
    </router-link>
    <!--/a-->

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
                <li class="nav-item">
                    <router-link tag="a" to="/berita" class="nav-link">
                        <i class="nav-icon fas fa-newspaper"></i>
                        <p>Berita</p>
                    </router-link>
                </li>
                <li class="nav-item">
                    <router-link tag="a" to="/galeri" class="nav-link">
                        <i class="nav-icon fas fa-photo-video"></i>
                        <p>Galeri</p>
                    </router-link>
                </li>
                <li class="nav-item">
                    <router-link tag="a" to="/faq" class="nav-link">
                        <i class="nav-icon fas fa-comments"></i>
                        <p>FAQ</p>
                    </router-link>
                </li>
                @endif
                @if($user->isAbleTo('referensi-read'))
                <li class="nav-item has-treeview">
                    <a class="nav-link" href="javascript:{}">
                        <i class="nav-icon fas fa-list"></i>
                        <p>Referensi
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @if($user->isAbleTo('referensi-create'))
                            <li class="nav-item">
                                <router-link tag="a" to="/komponen" class="nav-link">
                                    <i class="nav-icon fas fa-hand-point-right"></i>
                                    <p>Komponen</p>
                                </router-link>
                            </li>
                            <li class="nav-item">
                                <router-link tag="a" to="/aspek" class="nav-link">
                                    <i class="nav-icon fas fa-hand-point-right"></i>
                                    <p>Aspek</p>
                                </router-link>
                            </li>
                            <li class="nav-item">
                                <router-link tag="a" to="/atribut" class="nav-link">
                                    <i class="nav-icon fas fa-hand-point-right"></i>
                                    <p>Atribut</p>
                                </router-link>
                            </li>
                            <li class="nav-item">
                                <router-link tag="a" to="/indikator" class="nav-link">
                                    <i class="nav-icon fas fa-hand-point-right"></i>
                                    <p>Indikator Kinerja</p>
                                </router-link>
                            </li>
                            <li class="nav-item">
                                <router-link tag="a" to="/instrumen" class="nav-link">
                                    <i class="nav-icon fas fa-hand-point-right"></i>
                                    <p>Instrumen</p>
                                </router-link>
                            </li>
                        @endif
                        <li class="nav-item">
                            <router-link tag="a" to="/penjamin-mutu" class="nav-link">
                                <i class="nav-icon fas fa-hand-point-right"></i>
                                <p>Penjamin Mutu</p>
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <router-link tag="a" to="/sekolah" class="nav-link">
                                <i class="nav-icon fas fa-hand-point-right"></i>
                                <p>Sekolah</p>
                            </router-link>
                        </li>
                        @if($user->isAbleTo('users-create'))
                            <li class="nav-item">
                                <router-link tag="a" to="/pengguna" class="nav-link">
                                    <i class="nav-icon fas fa-hand-point-right"></i>
                                    <p>Pengguna</p>
                                </router-link>
                            </li>
                        @endif
                    </ul>
                </li>
                @endif
                @if($user->isAbleTo('jawaban-create'))
                <li class="nav-item">
                    <router-link tag="a" to="/kuisioner/pengisian" class="nav-link">
                        <i class="nav-icon fas fa-tasks"></i>
                        <p>Kuisioner</p>
                    </router-link>
                </li>
                <li class="nav-item has-treeview">
                    <a class="nav-link" href="javascript:{}">
                        <i class="nav-icon fas fa-check-double"></i>
                        <p>Rapor Mutu
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <router-link tag="a" to="/rapor-mutu/hasil" class="nav-link">
                                <i class="nav-icon fas fa-hand-point-right"></i>
                                <p>Hasil Rapor Mutu</p>
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <router-link tag="a" to="/rapor-mutu/pakta-integritas" class="nav-link">
                                <i class="nav-icon fas fa-hand-point-right"></i>
                                <p>Pakta Integritas</p>
                            </router-link>
                        </li>
                    </ul>
                </li>
                @endif
                @if($user->isAbleTo('verifikasi-create'))
                <li class="nav-item has-treeview">
                    <a class="nav-link" href="javascript:{}">
                        <i class="nav-icon fas fa-user-edit"></i>
                        <p>Verifikasi dan Supervisi
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <router-link tag="a" to="/hasil-verval" class="nav-link">
                                <i class="nav-icon fas fa-hand-point-right"></i>
                                <p>Hasil Verifikasi</p>
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <router-link tag="a" to="/kirim-laporan" class="nav-link">
                                <i class="nav-icon fas fa-hand-point-right"></i>
                                <p>Kirim Laporan Supervisi</p>
                            </router-link>
                        </li>
                    </ul>
                </li>
                @endif
                @if($user->isAbleTo('pengesahan-create'))
                <li class="nav-item has-treeview">
                    <a class="nav-link" href="javascript:{}">
                        <i class="nav-icon fas fa-edit"></i>
                        <p>Validasi dan Pengesahan
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <router-link tag="a" to="/hasil-validasi" class="nav-link">
                                <i class="nav-icon fas fa-hand-point-right"></i>
                                <p>Hasil Validasi</p>
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <router-link tag="a" to="/hasil-pengesahan" class="nav-link">
                                <i class="nav-icon fas fa-hand-point-right"></i>
                                <p>Hasil Pengesahan</p>
                            </router-link>
                        </li>
                    </ul>
                </li>
                @endif
                <li class="nav-item">
                    <a class="nav-link text-danger" href="javascript:{}" onclick="document.getElementById('logout-form-sidebar').submit();">
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