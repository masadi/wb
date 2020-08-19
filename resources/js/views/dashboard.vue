<template>
    <div class="no">
        <div class="content-header">
            <div class="container-fluid">
                <h1 class="m-0 text-dark">Beranda</h1>
            </div>
        </div>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div v-show="!user.sekolah_id" class="col-md-3 col-sm-6 col-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-info"><i class="fas fa-university"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Sekolah</span>
                                <span class="info-box-number">{{sekolah}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-info"><i class="fas fa-user-tie"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">PTK</span>
                                <span class="info-box-number">{{ptk}}</span>
                            </div>
                        </div>
                    </div>
                    <div v-show="user.sekolah_id" class="col-md-3 col-sm-6 col-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-success"><i class="fas fa-user-friends"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Peserta Didik</span>
                                <span class="info-box-number">{{pd}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-warning"><i class="fas fa-certificate"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Grade Personal</span>
                                <span class="info-box-number">{{grade_personal}}</span>
                            </div>
                        </div>
                    </div>
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
                                                <div class="img-handler-bot">
                                                    <img src="/images/icon_progres/1.png" width="150" alt="" />
                                                </div>
                                                <div class="ps-top">
                                                    <p>Proses pengisian instrumen</p>
                                                </div>
                                                <span class="ps-sp-bot"><i class="fas" v-bind:class="{ 'fa-check text-success': rapor.kuisioner, 'fa-times text-danger': !rapor.kuisioner }"></i></span>
                                            </li>
                                            <li>
                                                <div class="img-handler-top">
                                                    <img src="/images/icon_progres/2.png" width="150" alt=""
                                                        style="margin-bottom:80px;" />
                                                </div>
                                                <div class="ps-bot">
                                                    <p>Perhitungan rapor mutu sekolah</p>
                                                </div>
                                                <span class="ps-sp-top"><i class="fas" v-bind:class="{ 'fa-check text-success': rapor.hitung, 'fa-times text-danger': !rapor.hitung }"></i></i></span>
                                            </li>
                                            <li>
                                                <div class="img-handler-bot">
                                                    <img src="/images/icon_progres/3.png" width="200" alt="" />
                                                </div>
                                                <div class="ps-top">
                                                    <p>Cetak pakta integritas sekolah</p>
                                                </div>
                                                <span class="ps-sp-bot"><i class="fas" v-bind:class="{ 'fa-check text-success': rapor.pakta, 'fa-times text-danger': !rapor.pakta }"></i></i></span>
                                            </li>
                                            <li>
                                                <div class="img-handler-top">
                                                    <img src="/images/icon_progres/4.png" width="230" alt=""
                                                        style="margin-bottom:80px;" />
                                                </div>
                                                <div class="ps-bot">
                                                    <p>Verifikasi dan Validasi oleh verifikator</p>
                                                </div>
                                                <span class="ps-sp-top"><i class="fas" v-bind:class="{ 'fa-check text-success': rapor.verval, 'fa-times text-danger': !rapor.verval }"></i></i></span>
                                            </li>
                                            <li>
                                                <div class="img-handler-bot">
                                                    <img src="/images/icon_progres/5.png" width="150" alt="" />
                                                </div>
                                                <div class="ps-top">
                                                    <p>Input hasil verifikasi rapor mutu sekolah</p>
                                                </div>
                                                <span class="ps-sp-bot"><i class="fas" v-bind:class="{ 'fa-check text-success': rapor.verifikasi, 'fa-times text-danger': !rapor.verifikasi }"></i></i></span>
                                            </li>
                                            <li>
                                                <div class="img-handler-top">
                                                    <img src="/images/icon_progres/6.png" width="180" alt=""
                                                        style="margin-bottom:80px;" />
                                                </div>
                                                <div class="ps-bot">
                                                    <p>Hasil Rapor Mutu Sekolah dan Pengesahan</p>
                                                </div>
                                                <span class="ps-sp-top"><i class="fas" v-bind:class="{ 'fa-check text-success': rapor.pengesahan, 'fa-times text-danger': !rapor.pengesahan }"></i></i></span>
                                            </li>
                                        </ol>
                                    </div>
                                </section>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
            sekolah:0,
            ptk: 0,
            pd: 0,
            nilai: 0,
            grade_personal: 0,
            grade_sekolah: 0,
            sekolah_id: user.sekolah_id,
            rapor:{
                kuisioner : 0,
                hitung : 0,
                pakta : 0,
                verval : 0,
                verifikasi : 0,
                pengesahan : 0
            },
        }
    },
    methods: {
        loadPostsData() {
            axios.get(`/api/referensi/detil-sekolah`, {
                params: {
                    sekolah_id: this.sekolah_id,
                    user_id: user.user_id,
                }
            })
            .then((response) => {
                this.loading=false
                let getData = response.data.data
                this.sekolah = getData.sekolah
                this.ptk = getData.ptk_count
                this.pd = getData.pd_count
                this.nilai = getData.nilai
                this.grade_personal = getData.grade_personal
                this.grade_sekolah = getData.grade_sekolah
                this.rapor.kuisioner = getData.kuisioner
                this.rapor.hitung = getData.hitung
                this.rapor.pakta = getData.pakta
                this.rapor.verval = getData.verval
                this.rapor.verifikasi = getData.verifikasi
                this.rapor.pengesahan = getData.pengesahan
            })
        },
    },
}
</script>
