<template>

    <div class="no">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <h1 class="m-0 text-dark">Beranda</h1>
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-3 col-sm-6 col-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-info"><i class="fas fa-user-tie"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">PTK</span>
                                <span class="info-box-number">{{ptk}}</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->
                    <div class="col-md-3 col-sm-6 col-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-success"><i class="fas fa-user-friends"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Peserta Didik</span>
                                <span class="info-box-number">{{pd}}</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->
                    <div class="col-md-3 col-sm-6 col-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-warning"><i class="fas fa-certificate"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Grade Personal</span>
                                <span class="info-box-number">{{grade_personal}}</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->
                    <div class="col-md-3 col-sm-6 col-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-danger"><i class="far fa-star"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Grade Sekolah</span>
                                <span class="info-box-number">{{grade_sekolah}}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-user mr-1"></i>
                                    Selamat Datang {{user.name}}
                                </h3>
                            </div>
                            <div class="card-body">
                                    <section class="ps-timeline-sec">
                                        <div class="container">
                                            <ol class="ps-timeline">
                                                <li>
                                                    <div class="img-handler-top">
                                                        <img src="http://www.physology.co.uk/wp-content/uploads/2016/02/ps-elem_03.png" alt=""/>
                                                    </div>
                                                    <div class="ps-bot">
                                                        <p>Proses Pengisian Instrumen</p>
                                                    </div>
                                                    <span class="ps-sp-top"><i class="fas fa-check text-success"></i></span>
                                                </li>
                                                <li>
                                                    <div class="img-handler-bot">
                                                        <img src="http://www.physology.co.uk/wp-content/uploads/2016/02/ps-elem_13.png" alt=""/>
                                                    </div>
                                                    <div class="ps-top">
                                                        <p>Menghitung Rapor Mutu Sekolah</p>
                                                    </div>
                                                    <span class="ps-sp-bot"><i class="fas fa-times text-danger"></i></span>
                                                </li>
                                                <li>
                                                    <div class="img-handler-top">
                                                        <img src="http://www.physology.co.uk/wp-content/uploads/2016/02/ps-elem_05.png" alt=""/>
                                                    </div>
                                                    <div class="ps-bot">
                                                        <p>Pakta Integritas Sekolah</p>
                                                    </div>
                                                    <span class="ps-sp-top"><i class="fas fa-times text-danger"></i></span>
                                                </li>
                                                <li>
                                                    <div class="img-handler-bot">
                                                        <img src="http://www.physology.co.uk/wp-content/uploads/2016/02/ps-elem_10.png" alt=""/>
                                                    </div>
                                                    <div class="ps-top">
                                                        <p>Verifikasi dan Validasi Rapor Mutu Sekolah oleh Tim Direktorat</p>
                                                    </div>
                                                    <span class="ps-sp-bot"><i class="fas fa-times text-danger"></i></span>
                                                </li>
                                                <li>
                                                    <div class="img-handler-top">
                                                        <img src="http://www.physology.co.uk/wp-content/uploads/2016/02/ps-elem_05.png" alt=""/>
                                                    </div>
                                                    <div class="ps-bot">
                                                        <p>Pakta Integritas Tim Verifikator</p>
                                                    </div>
                                                    <span class="ps-sp-top"><i class="fas fa-times text-danger"></i></span>
                                                </li>
                                                <li>
                                                    <div class="img-handler-bot">
                                                        <img src="http://www.physology.co.uk/wp-content/uploads/2016/02/ps-elem_10.png" alt=""/>
                                                    </div>
                                                    <div class="ps-top">
                                                        <p>Pengesahan oleh Direktorat</p>
                                                    </div>
                                                    <span class="ps-sp-bot"><i class="fas fa-times text-danger"></i></span>
                                                </li>
                                            </ol>
                                        </div>
                                    </section>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
    </div>
</template>
<script>
import axios from 'axios' //IMPORT AXIOS
export default {
    //KETIKA COMPONENT INI DILOAD
    created() {
        //MAKA AKAN MENJALANKAN FUNGSI BERIKUT
        this.loadPostsData()
    },
    data() {
        return {
            user: user,
            ptk: 0,
            pd: 0,
            nilai: 0,
            grade_personal: 0,
            grade_sekolah: 0,
            sekolah_id: user.sekolah_id,
        }
    },
    methods: {
        loadPostsData() {
            axios.get(`/api/referensi/detil-sekolah`, {
                params: {
                    sekolah_id: this.sekolah_id,
                }
            })
            .then((response) => {
                let getData = response.data.data
                this.ptk = getData.ptk_count
                this.pd = getData.pd_count
                this.nilai = getData.nilai
                this.grade_personal = getData.grade_personal
                this.grade_sekolah = getData.grade_sekolah
            })
        },
    },
}
</script>
