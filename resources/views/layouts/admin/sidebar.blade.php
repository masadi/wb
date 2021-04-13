<?php
$user = auth()->user();
?>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{url('/')}}" class="brand-link">
        <!--router-link tag="a" to="/beranda" class="brand-link"-->
        <img src="/images/AdminLTELogo.png" alt="{{ config('app.name', 'Laravel') }}"
            class="brand-image img-circle elevation-3" style="opacity: .8">
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
                @if($user->isAbleTo('users-create'))
                <li class="nav-item has-treeview">
                    <a class="nav-link" href="javascript:{}">
                        <i class="nav-icon fas fa-list"></i>
                        <p>Data Master
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <router-link tag="a" to="/master/kurs-dollar" class="nav-link">
                                <i class="nav-icon fas fa-search-dollar"></i>
                                <p>Kurs Dollar</p>
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <router-link tag="a" to="/master/trader" class="nav-link">
                                <i class="nav-icon fas fa-tags orange"></i>
                                <p>
                                    Data Trader
                                </p>
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <router-link tag="a" to="/master/sub-ib" class="nav-link">
                                <i class="nav-icon fas fa-list-ol orange"></i>
                                <p>
                                    Data SUB IB
                                </p>
                            </router-link>
                        </li>
                    </ul>
                </li>
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-cog green"></i>
                        <p>
                            Data Transaksi
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">

                        <li class="nav-item">
                            <router-link to="/transaksi/upload" class="nav-link">
                                <i class="nav-icon fas fa-list-ol green"></i>
                                <p>
                                    Upload File Rebate
                                </p>
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <router-link to="/transaksi/rebate" class="nav-link">
                                <i class="nav-icon fas fa-tags green"></i>
                                <p>
                                    Data Rebate Trader
                                </p>
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <router-link to="/transaksi/komisi" class="nav-link">
                                <i class="nav-icon fas fa-tags green"></i>
                                <p>
                                    Data Komisi SUB IB
                                </p>
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <router-link to="/transaksi/trader" class="nav-link">
                                <i class="nav-icon fas fa-tags green"></i>
                                <p>
                                    Trader Baru
                                </p>
                            </router-link>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <router-link to="/users" class="nav-link">
                        <i class="fa fa-users nav-icon blue"></i>
                        <p>Data Pengguna</p>
                    </router-link>
                </li>
                @endif
                <!--
                <li class="nav-item">
                    <router-link tag="a" to="/pengguna" class="nav-link">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Pengguna</p>
                    </router-link>
                </li>
                -->
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