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
                            <div class="card-body" v-show="!is_coe">
                                <p>Sekolah Anda belum ditetapkan sebagai SMK Center of Excelent</p>
                            </div>
                            <div class="card-body" v-show="is_coe">
                                    <div class="row">
                                        <div class="col-md-3 text-center border-right">
                                            <div class="text-lg text-center">Kemajuan Pengisian Instrumen</div>
                                            <center><canvas id="kemajuan" style="height: 700px; width: 100%;"></canvas></center>
                                        </div>
                                        <div class="col-md-3 text-center border-right">
                                            <div class="text-lg text-center">Nilai Rapor Mutu Sekolah</div>
                                            <center><canvas id="nilai_rapor" style="height: 700px; width: 100%;"></canvas></center>
                                        </div>
                                        <div class="col-md-6 text-center">
                                            <div class="text-lg text-center">Nilai Komponen Mutu Sekolah</div>
                                            <center><canvas id="nilai_komponen"></canvas></center>
                                        </div>
                                    </div>
                            </div>
                        </section>
                        <div class="card">
                            <div class="card-body">
                                <section class="ps-timeline-sec" v-show="hasRole('sekolah')">
                                    <div class="container" v-show="is_coe">
                                        <!--exclamation-->
                                        <ol class="ps-timeline">
                                            <li>
                                                <div class="img-handler-bot">
                                                    <img src="/images/icon_progres/1.png" width="150" alt="" />
                                                </div>
                                                <div class="ps-top">
                                                    <p>Proses pengisian instrumen</p>
                                                </div>
                                                <span class="ps-sp-bot"><i class="fas" v-bind:class="{ 'fa-check text-success': rapor.instrumen }"></i></span>
                                            </li>
                                            <li>
                                                <div class="img-handler-top">
                                                    <img src="/images/icon_progres/2.png" width="150" alt=""
                                                        style="margin-bottom:80px;" />
                                                </div>
                                                <div class="ps-bot">
                                                    <p>Perhitungan rapor mutu sekolah</p>
                                                </div>
                                                <span class="ps-sp-top"><i class="fas" v-bind:class="{ 'fa-check text-success': rapor.hitung }"></i></i></span>
                                            </li>
                                            <li>
                                                <div class="img-handler-bot">
                                                    <img src="/images/icon_progres/3.png" width="200" alt="" />
                                                </div>
                                                <div class="ps-top">
                                                    <p>Cetak pakta integritas sekolah</p>
                                                </div>
                                                <span class="ps-sp-bot"><i class="fas" v-bind:class="{ 'fa-check text-success': rapor.pakta }"></i></i></span>
                                            </li>
                                            <li>
                                                <div class="img-handler-top">
                                                    <img src="/images/icon_progres/4.png" width="230" alt=""
                                                        style="margin-bottom:80px;" />
                                                </div>
                                                <div class="ps-bot">
                                                    <p>Verifikasi dan Validasi oleh Tim Penjamin Mutu</p>
                                                </div>
                                                <span class="ps-sp-top"><i class="fas" v-bind:class="{ 'fa-check text-success': rapor.verval }"></i></i></span>
                                            </li>
                                            <li>
                                                <div class="img-handler-bot">
                                                    <img src="/images/icon_progres/5.png" width="150" alt="" />
                                                </div>
                                                <div class="ps-top">
                                                    <p>Verifikasi oleh Direktorat</p>
                                                </div>
                                                <span class="ps-sp-bot"><i class="fas" v-bind:class="{ 'fa-check text-success': rapor.proses }"></i></i></span>
                                            </li>
                                            <li>
                                                <div class="img-handler-top">
                                                    <img src="/images/icon_progres/6.png" width="180" alt=""
                                                        style="margin-bottom:80px;" />
                                                </div>
                                                <div class="ps-bot">
                                                    <p>Hasil Rapor Mutu Sekolah dan Pengesahan</p>
                                                </div>
                                                <span class="ps-sp-top"><i class="fas" v-bind:class="{ 'fa-check text-success': rapor.terima, 'fa-times text-danger': rapor.tolak }"></i></i></span>
                                            </li>
                                        </ol>
                                    </div>
                                </section>
                                <section v-show="hasRole('admin')">
                                    Content akses admin
                                </section>
                                <section v-show="hasRole('direktorat')">
                                    Content akses direktorat
                                </section>
                                <section v-show="hasRole('penjamin_mutu')">
                                    <div class="row">
                                        <div class="col-lg-3 col-6">
                                            
                                            <div class="small-box bg-info">
                                            <div class="inner">
                                                <h3>{{jml_sekolah_sasaran}}</h3>
                                                <p>Jumlah Sekolah Sasaran</p>
                                            </div>
                                            <div class="icon">
                                                <i class="ion ion-bag"></i>
                                            </div>
                                            <a href="#" class="small-box-footer">&nbsp;</a>
                                            </div>
                                        </div>
                                        
                                        <div class="col-lg-3 col-6">
                                            
                                            <div class="small-box bg-warning">
                                            <div class="inner">
                                                <h3>{{jml_sekolah_sasaran_no_instrumen}}</h3>

                                                <p>Sekolah Sasaran Belum Mengisi Instrumen</p>
                                            </div>
                                            <div class="icon">
                                                <i class="ion ion-person-add"></i>
                                            </div>
                                            <a href="#" class="small-box-footer">&nbsp;</a>
                                            </div>
                                        </div>
                                        
                                        <div class="col-lg-3 col-6">
                                            
                                            <div class="small-box bg-success">
                                            <div class="inner">
                                                <h3>{{jml_sekolah_sasaran_instrumen}}</h3>

                                                <p>Sekolah Sasaran Telah Mengisi Instrumen</p>
                                            </div>
                                            <div class="icon">
                                                <i class="ion ion-stats-bars"></i>
                                            </div>
                                            <a href="#" class="small-box-footer">&nbsp;</a>
                                            </div>
                                        </div>
                                        
                                        <div class="col-lg-3 col-6">
                                            
                                            <div class="small-box bg-danger">
                                            <div class="inner">
                                                <h3>{{jml_sekolah_sasaran_verval}}</h3>

                                                <p>Sekolah Sasaran Telah di Verval</p>
                                            </div>
                                            <div class="icon">
                                                <i class="ion ion-pie-graph"></i>
                                            </div>
                                            <a href="#" class="small-box-footer">&nbsp;</a>
                                            </div>
                                        </div>
                                    
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
            is_coe: null,
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
                proses : 0,
                terima : 0,
                tolak: 0,
            },
            jml_sekolah_sasaran: 0,
            jml_sekolah_sasaran_instrumen: 0,
            jml_sekolah_sasaran_no_instrumen: 0,
            jml_sekolah_sasaran_verval: 0,
        }
    },
    //mounted() {
        //this.createChart('kemajuan', this.planetChartData);
    //},
    methods: {
        createChart(chartId, chartData) {
            if(chartData){
                const ctx = document.getElementById(chartId);
                const myChart = new Chart(ctx, {
                    type: chartData.type,
                    data: chartData.data,
                    options: chartData.options,
                });
            }
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
                if(!getData){
                    return false
                }
                this.is_coe = getData.smk_coe
                this.jml_sekolah_sasaran = getData.jml_sekolah_sasaran
                this.jml_sekolah_sasaran_instrumen = getData.jml_sekolah_sasaran_instrumen
                this.jml_sekolah_sasaran_no_instrumen = getData.jml_sekolah_sasaran_no_instrumen
                this.jml_sekolah_sasaran_verval = getData.jml_sekolah_sasaran_verval
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
                this.rapor.proses = getData.proses
                this.rapor.terima = getData.terima
                this.rapor.tolak = getData.tolak
                this.createChart('kemajuan', getData.kemajuan)
                this.createChart('nilai_rapor', getData.nilai_rapor)
                if(getData.nilai_komponen){
                    var barChartData = {
                        //labels: ['Input', 'Proses', 'Output', 'Outcome', 'Impact'],
                        labels: getData.nilai_komponen.labels,
                        datasets: [{
                            label: 'Nilai Komponen Tercapai',
                            backgroundColor: ['#d9434e', '#1fac4d', '#48cfc1', '#9398ec', '#d27b25'],
                            borderColor: '#f4f7ec',
                            borderWidth: 1,
                            data: getData.nilai_komponen.nilai_tercapai,
                            bobot: getData.nilai_komponen.bobot_tercapai
                        },
                        {
                            label: 'Nilai Komponen Ideal',
                            backgroundColor: ['#D3D3D3', '#D3D3D3', '#D3D3D3', '#D3D3D3', '#D3D3D3'],
                            borderColor: '#f4f7ec',
                            borderWidth: 1,
                            data: getData.nilai_komponen.nilai_belum_tercapai,
                            bobot: getData.nilai_komponen.bobot_belum_tercapai
                        }]
                    }
                    let ctx_bar = document.getElementById('nilai_komponen')
                    new Chart(ctx_bar, {
                        type: 'bar',
                        data: barChartData,
                        options: {
                            responsive: true,
                            legend: {
                                display: false,
                                position: 'top',
                            },
                            title: {
                                display: false,
                                text: 'Chart.js Bar Chart'
                            },
                            scales: {
                                xAxes: [{
                                    stacked: true
                                }],
                                yAxes: [{
                                    ticks: {
                                        beginAtZero:true,
                                        max:100
                                    },
                                    stacked: true
                                }]
                            },
                            onClick: function (e) {
                                //console.log(e);
                            },
                            tooltips: {
                                mode: 'index',
                                callbacks: {
                                    title: function(tooltipItems, data) {
                                        var title = '';
                                        tooltipItems.forEach(function(tooltipItem) {
                                            title = tooltipItem.xLabel
                                        })
                                        return 'Ketercapaian Komponen '+title
                                    },
                                    label: function(tooltipItem, data) {
                                        var label = data.datasets[tooltipItem.datasetIndex].label || '';

                                        if (label) {
                                            label += ': ';
                                        }
                                        if(tooltipItem.datasetIndex == 1){
                                            label += '100'
                                        } else {
                                            label += Math.round(tooltipItem.yLabel * 100) / 100;
                                        }
                                        return label+'%';
                                    },
                                    footer: function(tooltipItems, data) {
                                        var sum = 0;
                                        var bobot_tercapai = 0;
                                        var bobot_belum_tercapai = 0;
                                        tooltipItems.forEach(function(tooltipItem) {
                                            bobot_tercapai = data.datasets[0].bobot[tooltipItem.index];
                                            bobot_belum_tercapai = data.datasets[1].bobot[tooltipItem.index];
                                            if(tooltipItem.datasetIndex == 0){
                                                sum += data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                                            }
                                        });
                                        var persentase = (100 - sum);
                                        persentase = persentase.toFixed(2)
                                        var _return = 'Bobot tercapai:'+bobot_tercapai+'\n'
                                        _return += 'Bobot belum tercapai:'+bobot_belum_tercapai+'\n'
                                        _return += 'Persentase Ketidaktercapaian: ' + persentase+'%'
                                        return _return;
                                    },
                                },
                                footerFontStyle: 'normal'
                            },
                        }
                    });
                }
            })
        },
    },
}
</script>
