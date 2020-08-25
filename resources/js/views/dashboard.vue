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
                    <div class="col-12">
                        <section class="card" v-show="hasRole('sekolah')">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3 text-center border-right">
                                        <div class="text-lg text-center">Kemajuan Pengisian Instrumen</div>
                                        <center><canvas id="kemajuan" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 250px;"></canvas></center>
                                    </div>
                                    <div class="col-md-3 text-center border-right">
                                        <div class="text-lg text-center">Nilai Rapor Mutu Sekolah</div>
                                        <center><canvas id="nilai_rapor" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 250px;"></canvas></center>
                                    </div>
                                    <div class="col-md-6 text-center">
                                        <div class="text-lg text-center">Nilai Komponen Mutu Sekolah</div>
                                        <center><canvas id="nilai_komponen"></canvas></center>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <div class="card">
                            <!--div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-user mr-1"></i>
                                    Selamat Datang {{user.name}}
                                </h3>
                            </div-->
                            <div class="card-body">
                                <section v-show="hasRole('admin')">
                                    Content akses admin
                                </section>
                                <section v-show="hasRole('direktorat')">
                                    Content akses direktorat
                                </section>
                                <section v-show="hasRole('penjamin_mutu')">
                                    Content akses tim penjamin mutu
                                </section>
                                <section class="ps-timeline-sec" v-show="hasRole('sekolah')">
                                    <div class="container">
                                        <ol class="ps-timeline">
                                            <li>
                                                <div class="img-handler-bot">
                                                    <img src="/images/icon_progres/1.png" width="150" alt="" />
                                                </div>
                                                <div class="ps-top">
                                                    <p>Proses pengisian instrumen</p>
                                                </div>
                                                <span class="ps-sp-bot"><i class="fas" v-bind:class="{ 'fa-check text-success': rapor.instrumen, 'fa-times text-danger': !rapor.instrumen }"></i></span>
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
                                                    <p>Verifikasi dan Validasi oleh Tim Penjamin Mutu</p>
                                                </div>
                                                <span class="ps-sp-top"><i class="fas" v-bind:class="{ 'fa-check text-success': rapor.verval, 'fa-times text-danger': !rapor.verval }"></i></i></span>
                                            </li>
                                            <li>
                                                <div class="img-handler-bot">
                                                    <img src="/images/icon_progres/5.png" width="150" alt="" />
                                                </div>
                                                <div class="ps-top">
                                                    <p>Verifikasi oleh Direktorat</p>
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
        <my-loader/>
    </div>
</template>
<script>
import Chart from 'chart.js';
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
                instrumen : 0,
                hitung : 0,
                pakta : 0,
                verval : 0,
                verifikasi : 0,
                pengesahan : 0
            },
        }
    },
    //mounted() {
        //this.createChart('kemajuan', this.planetChartData);
    //},
    methods: {
        createChart(chartId, chartData) {
            //console.log(chartData)
            const ctx = document.getElementById(chartId);
            const myChart = new Chart(ctx, {
                type: chartData.type,
                data: chartData.data,
                options: chartData.options,
            });
        },
        randomScalingFactor(){
            return Math.floor(Math.random() * 101);
        },
        loadPostsData() {
            axios.post(`/api/sekolah`, {
                user_id: user.user_id,
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
                this.rapor.instrumen = getData.instrumen
                this.rapor.hitung = getData.hitung
                this.rapor.pakta = getData.pakta
                this.rapor.verval = getData.verval
                this.rapor.verifikasi = getData.verifikasi
                this.rapor.pengesahan = getData.pengesahan
                this.createChart('kemajuan', getData.kemajuan)
                this.createChart('nilai_rapor', getData.nilai_rapor)
                this.createChart('nilai_komponen', getData.nilai_komponen)
            })
        },
    },
}
</script>
