<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Rapor Mutu Sekolah</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><router-link tag="a" to="/beranda">Beranda</router-link></li>
                            <li class="breadcrumb-item active">Rapor Mutu Sekolah</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-center">
                                    <button type="button" class="btn btn-primary btn-lg btn-flat mb-3" :disabled='diMatikan' v-on:click="hitung_rapor_mutu"><span class="h4"><i class="fas fa-clipboard-check"></i> HITUNG RAPOR MUTU SEKOLAH</span></button>
                                </div>
                                <div class="row">
                                    <ul class="timeline" id="timeline">
                                        <li class="li" v-bind:class="{ complete: rapor_mutu.instrumen }">
                                            <div class="timestamp">
                                                <span class="date">{{(rapor_mutu.instrumen) ? rapor_mutu.instrumen : '-'}}</span>
                                            </div>
                                            <div class="status">
                                                <h4 class="khusus_timeline text-center" v-bind:class="{ checklist: rapor_mutu.instrumen }"> Mengisi Kuisioner </h4>
                                            </div>
                                        </li>
                                        <li class="li" v-bind:class="{ complete: rapor_mutu.hitung }">
                                            <div class="timestamp">
                                                <span class="date">{{(rapor_mutu.hitung) ? rapor_mutu.hitung : '-'}}</span>
                                            </div>
                                            <div class="status">
                                                <h4 class="khusus_timeline text-center" v-bind:class="{ checklist: rapor_mutu.hitung }"> Mengitung Rapor Mutu Sekolah </h4>
                                            </div>
                                        </li>
                                        <li class="li" v-bind:class="{ complete: rapor_mutu.pakta }">
                                            <div class="timestamp">
                                                <span class="date">{{(rapor_mutu.pakta) ? rapor_mutu.pakta : '-'}}</span>
                                            </div>
                                            <div class="status">
                                                <h4 class="khusus_timeline text-center" v-bind:class="{ checklist: rapor_mutu.pakta }"> Cetak Pakta Integritas Sekolah </h4>
                                            </div>
                                        </li>
                                        <li class="li" v-bind:class="{ complete: rapor_mutu.verval }">
                                            <div class="timestamp">
                                                <span class="date">{{(rapor_mutu.verval) ? rapor_mutu.verval : '-'}}</span>
                                            </div>
                                            <div class="status">
                                                <h4 class="khusus_timeline text-center" v-bind:class="{ checklist: rapor_mutu.verval }"> Verval oleh Tim Penjamin Mutu </h4>
                                            </div>
                                        </li>
                                        <li class="li" v-bind:class="{ complete: rapor_mutu.proses }">
                                            <div class="timestamp">
                                                <span class="date">{{(rapor_mutu.proses) ? rapor_mutu.proses : '-'}}</span>
                                            </div>
                                            <div class="status">
                                                <h4 class="khusus_timeline text-center" v-bind:class="{ checklist: rapor_mutu.proses }"> Verifikasi oleh Direktorat </h4>
                                            </div>
                                        </li>
                                        <li class="li" v-bind:class="{ complete: rapor_mutu.terima }">
                                            <div class="timestamp">
                                                <span class="date" v-show="rapor_mutu.terima">{{(rapor_mutu.terima) ? rapor_mutu.terima :'-'}}</span>
                                                <span class="date" v-show="rapor_mutu.tolak">{{(rapor_mutu.tolak) ? rapor_mutu.tolak :'-'}}</span>
                                                <span class="date" v-show="!rapor_mutu.tolak && !rapor_mutu.terima">-</span>
                                            </div>
                                            <div class="status">
                                                <h4 class="khusus_timeline text-center" v-bind:class="{ checklist: rapor_mutu.terima, unchecklist : rapor_mutu.tolak }"> Pengesahan oleh Direktorat </h4>
                                            </div>
                                        </li>
                                    </ul> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">                               
                                <div class="chartdiv" id="chartdiv" ref="chartdiv" style="width: 100%;height: 500px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--b-row class="mb-3">
                    <b-col lg="12" class="text-center">
                        <b-button squared :disabled='!rapor.kuisioner.lengkap' v-on:click="cetak_rapor_mutu(data_lengkap)" size="lg" variant="primary">
                            <b-spinner small v-show="show_spinner_cetak" style="width: 3rem; height: 3rem;" label="Large Spinner"></b-spinner>
                            <span class="sr-only" v-show="show_spinner_cetak">Loading...</span>
                            <span class="h4" v-show="show_text_cetak"><i class="fas fa-print"></i> CETAK RAPOR MUTU SEKOLAH</span>
                        </b-button>
                    </b-col>
                </b-row-->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">Nama Sekolah</th>
                                            <th class="text-center" scope="col">Nilai Rapor Mutu</th>
                                            <th class="text-center" scope="col">Predikat</th>
                                            <th class="text-center" scope="col">Kategori</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{nama_sekolah}}</td>
                                            <td class="text-center">{{nilai_rapor_mutu}}</td>
                                            <td class="text-center">{{predikat_sekolah}}</td>
                                            <td class="text-center">
                                                <span class="fa fa-star" v-bind:class="{'bintang': nilai_rapor_mutu >= 1}"></span>
                                                <span class="fa fa-star" v-bind:class="{'bintang': nilai_rapor_mutu >= 21}"></span>
                                                <span class="fa fa-star" v-bind:class="{'bintang': nilai_rapor_mutu >= 41}"></span>
                                                <span class="fa fa-star" v-bind:class="{'bintang': nilai_rapor_mutu >= 61}"></span>
                                                <span class="fa fa-star" v-bind:class="{'bintang': nilai_rapor_mutu >= 81}"></span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th class="text-center" scope="col">No</th>
                                            <th class="text-center" scope="col">Komponen</th>
                                            <th class="text-center" scope="col">Nilai</th>
                                            <th class="text-center" scope="col">Predikat</th>
                                            <th class="text-center" scope="col">Kategori</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(kuisioner, key) in kuisioners">
                                            <td class="text-center">{{key + 1}}</td>
                                            <td><span @click="batang(kuisioner.id)">{{kuisioner.nama}}</span></td>
                                            <td class="text-center">{{(kuisioner.nilai_komponen) ? kuisioner.nilai_komponen.total_nilai : 0}}</td>
                                            <td class="text-center">{{(kuisioner.nilai_komponen) ? kuisioner.nilai_komponen.predikat : '-'}}</td>
                                            <td class="text-center">
                                                <span class="fa fa-star" v-bind:class="{'bintang': bintangKomponen[kuisioner.id] >= 1}"></span>
                                                <span class="fa fa-star" v-bind:class="{'bintang': bintangKomponen[kuisioner.id] >= 21}"></span>
                                                <span class="fa fa-star" v-bind:class="{'bintang': bintangKomponen[kuisioner.id] >= 41}"></span>
                                                <span class="fa fa-star" v-bind:class="{'bintang': bintangKomponen[kuisioner.id] >= 61}"></span>
                                                <span class="fa fa-star" v-bind:class="{'bintang': bintangKomponen[kuisioner.id] >= 81}"></span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th class="text-center" scope="col" width="60%" colspan="4">Komponen / Aspek / Indikator</th>
                                            <th class="text-center" scope="col" width="10%">Nilai</th>
                                            <th class="text-center" scope="col" width="15%">Predikat</th>
                                            <th class="text-center" scope="col" width="15%">Kategori</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template v-for="(kuisioner, key) in kuisioners">
                                            <tr class="bg-success">
                                                <td class="text-right">{{key+1}}</td>
                                                <td colspan="3">{{kuisioner.nama}}</td>
                                                <td class="text-center">{{(kuisioner.nilai_komponen) ? kuisioner.nilai_komponen.total_nilai : 0}}</td>
                                                <td class="text-center">{{(kuisioner.nilai_komponen) ? kuisioner.nilai_komponen.predikat : '-'}}</td>
                                                <td class="text-center">
                                                    <span class="fa fa-star" v-bind:class="{'bintang': bintangKomponen[kuisioner.id] >= 1}"></span>
                                                    <span class="fa fa-star" v-bind:class="{'bintang': bintangKomponen[kuisioner.id] >= 21}"></span>
                                                    <span class="fa fa-star" v-bind:class="{'bintang': bintangKomponen[kuisioner.id] >= 41}"></span>
                                                    <span class="fa fa-star" v-bind:class="{'bintang': bintangKomponen[kuisioner.id] >= 61}"></span>
                                                    <span class="fa fa-star" v-bind:class="{'bintang': bintangKomponen[kuisioner.id] >= 81}"></span>
                                                </td>
                                            </tr>
                                            <!--tr>
                                                <td colspan="7">
                                                    asd
                                                </td>
                                            </tr-->
                                            <template v-for="(aspek, sub_key) in kuisioner.aspek">
                                                <tr>
                                                    <td class="text-right"></td>
                                                    <td class="text-right">{{key+1}}.{{sub_key+1}}</td>
                                                    <td colspan="2">{{aspek.nama}}</td>
                                                    <td class="text-center">{{(aspek.nilai_aspek) ? aspek.nilai_aspek.total_nilai : 0}}</td>
                                                    <td class="text-center">{{(aspek.nilai_aspek) ? aspek.nilai_aspek.predikat : '-'}}</td>
                                                    <td class="text-center">
                                                        <span class="fa fa-star" v-bind:class="{'bintang': bintangAspek[aspek.id] >= 1}"></span>
                                                        <span class="fa fa-star" v-bind:class="{'bintang': bintangAspek[aspek.id] >= 21}"></span>
                                                        <span class="fa fa-star" v-bind:class="{'bintang': bintangAspek[aspek.id] >= 41}"></span>
                                                        <span class="fa fa-star" v-bind:class="{'bintang': bintangAspek[aspek.id] >= 61}"></span>
                                                        <span class="fa fa-star" v-bind:class="{'bintang': bintangAspek[aspek.id] >= 81}"></span>
                                                    </td>
                                                </tr>
                                                <template v-for="(instrumen, sub_sub_key) in aspek.instrumen">
                                                    <tr>
                                                        <td class="text-right"></td>
                                                        <td class="text-right"></td>
                                                        <td style="vertical-align:middle;" class="text-right">{{key+1}}.{{sub_key+1}}.{{sub_sub_key+1}}</td>
                                                        <td>{{instrumen.pertanyaan}}</td>
                                                        <td class="text-center">{{(instrumen.nilai_instrumen) ? instrumen.nilai_instrumen.nilai : 0}}</td>
                                                        <td class="text-center">{{(instrumen.nilai_instrumen) ? instrumen.nilai_instrumen.predikat : '-'}}</td>
                                                        <td class="text-center">
                                                            <span class="fa fa-star" v-bind:class="{'bintang': bintangInstrumen[instrumen.instrumen_id] >= 1}"></span>
                                                            <span class="fa fa-star" v-bind:class="{'bintang': bintangInstrumen[instrumen.instrumen_id] >= 2}"></span>
                                                            <span class="fa fa-star" v-bind:class="{'bintang': bintangInstrumen[instrumen.instrumen_id] >= 3}"></span>
                                                            <span class="fa fa-star" v-bind:class="{'bintang': bintangInstrumen[instrumen.instrumen_id] >= 4}"></span>
                                                            <span class="fa fa-star" v-bind:class="{'bintang': bintangInstrumen[instrumen.instrumen_id] >= 5}"></span>
                                                        </td>
                                                    </tr>
                                                </template>
                                            </template>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <my-loader/>
        <b-modal ref="my-modal" hide-footer title="Using Component Methods">
            <template v-slot:modal-title>
            Using <code>$bvModal</code> Methods
            </template>
            <div class="d-block text-center">
                <batang :isi_batang="isi_batang"></batang>
            </div>
        </b-modal>
    </div>
</template>
<style lang="scss" scoped>
  @import './../../../../public/css/timeline_simple.scss'; /* injected */
</style>
<script>
//import Chart from 'chart.js';
import * as am4core from "@amcharts/amcharts4/core";
import * as am4charts from "@amcharts/amcharts4/charts";
import am4themes_animated from "@amcharts/amcharts4/themes/animated";

am4core.useTheme(am4themes_animated);
//import RaporMutu from './../components/RaporMutu.vue'
import Batang from './../components/Batang.vue'
import axios from 'axios' //IMPORT AXIOS
export default {
    created() {
        this.loadPostsData()
    },
    data() {
        return {
            all_komponen: [1, 2, 3, 4, 5, 6],
            id_komponen: [],
            user: user,
            rapor_mutu:{
                instrumen : 0,
                hitung : 0,
                pakta : 0,
                verval : 0,
                verifikasi : 0,
                pengesahan : 0
            },
            rapor:{
                kuisioner : {
                    lengkap: 0,
                    tgl : '-'
                },
                hitung : {
                    lengkap: 0,
                    tgl : '-'
                },
                pakta : {
                    lengkap: 0,
                    tgl : '-'
                },
                verval : {
                    lengkap: 0,
                     tgl : '-'
                },
                proses : {
                    lengkap: 0,
                     tgl : '-'
                },
                terima : {
                    lengkap: 0,
                     tgl : '-'
                },
                tolak : {
                    lengkap: 0,
                     tgl : '-'
                },
                verifikator_id : null
            },
            sekolah_id: user.sekolah_id,
            kuisioners: [],
            output_indikator: [],
            bintangKomponen: {},
            bintangAspek: {},
            bintangInstrumen: {},
            nama_sekolah: '',
            nilai_rapor_mutu: 0,
            predikat_sekolah: '-',
            data_lengkap : null,
            show_spinner_cetak: false,
            show_text_cetak: true,
            chartData: [],
            isi_batang: null,
        }
    },
    components: {
        'batang' : Batang,
    },
    beforeDestroy() {
        if (this.chart) {
            this.chart.dispose();
        }
    },
    computed: {
        diMatikan(){
            if(!this.rapor.kuisioner.lengkap){
                return true
            } else if(this.rapor.pakta.lengkap){
                return true
            } else if(!this.rapor.verifikator_id){
                return true
            } else {
                return false
            }
        }
    },
    methods: {
        batang(id){
            this.isi_batang = id
            this.$refs['my-modal'].show()
        },
        createChart(chartID, chartData) {
            //console.log(this.$refs[chartID]);
            if(chartData){
                (function () {
                    var chart = am4core.create(
                        document.getElementById(chartID),
                        am4charts.XYChart3D
                    );
                    //let chart = am4core.create(this.$refs.chartdiv, am4charts.XYChart3D);
                    chart.colors.list = [
                        am4core.color("#33bec0"),
                        am4core.color("#D3D3D3"),
                        //am4core.color("#FF6F91"),
                        //am4core.color("#FF9671"),
                        //am4core.color("#FFC75F")
                    ];
                    chart.tooltip.label.fill = am4core.color("#ffffff");
                    chart.data = chartData;
                    // Create axes
                    var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
                    categoryAxis.dataFields.category = "komponen";
                    categoryAxis.renderer.grid.template.location = 0;
                    categoryAxis.renderer.minGridDistance = 30;

                    var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
                    valueAxis.title.text = "Ketercapaian Komponen";
                    valueAxis.min = 0;
                    valueAxis.max = 100;
                    valueAxis.strictMinMax = true; 
                    valueAxis.renderer.labels.template.adapter.add("text", function(text) {
                        return text + "%";
                    });
                    // Create series
                    var series = chart.series.push(new am4charts.ColumnSeries3D());
                    series.dataFields.valueY = "tercapai";
                    series.dataFields.categoryX = "komponen";
                    series.name = "Komponen Tercapai";
                    series.clustered = false;
                    series.columns.template.tooltipHTML = "<center>Komponen tercapai: <br> <strong>{valueY}</strong></center>";
                    series.columns.template.fillOpacity = 0.9;
                    series.columns.template.showTooltipOn = "always";
                    series.tooltip.pointerOrientation = "top";
                    series.columns.template.width = am4core.percent(50);
                    /*series.columns.template.events.on("hit", function(ev) {
                        console.log("clicked on ", ev.target);
                    }, this);*/
                    var bullet = series.bullets.push(new am4charts.LabelBullet())
                    bullet.interactionsEnabled = false
                    bullet.dy = 90;
                    bullet.label.text = '{valueY}%'
                    bullet.label.fill = am4core.color('#ffffff')
                    var series2 = chart.series.push(new am4charts.ColumnSeries3D());
                    series2.dataFields.valueY = "total";
                    series2.dataFields.setTitle = "belum_tercapai";
                    series2.dataFields.categoryX = "komponen";
                    series2.name = "Komponen belum Tercapai";
                    series2.clustered = false;
                    series2.columns.template.tooltipHTML = "<center>Komponen belum tercapai: <br> <strong>{setTitle}</strong></center>";
                    series2.columns.template.fillOpacity = 0.9;
                    series2.columns.template.showTooltipOn = "always";
                    series2.columns.template.tooltipY = 10;
                    series2.columns.template.width = am4core.percent(60);
                    series2.tooltip.pointerOrientation = "down";
                    var bullet2 = series2.bullets.push(new am4charts.LabelBullet())
                    bullet2.interactionsEnabled = false
                    bullet2.dy = 10;
                    bullet2.label.text = '{setTitle}%'
                    bullet2.label.fill = am4core.color('red')
                    chart.exporting.menu = new am4core.ExportMenu();
                })();
            }
        },
        loadPostsData() {
            axios.post(`/api/rapor-mutu/hasil`, {
                user_id: user.user_id,
            }).then((response) => {
                let getData = response.data
                this.data_lengkap = getData.detil_user
                this.rapor_mutu = {
                    instrumen : getData.rapor_mutu.instrumen,
                    hitung : getData.rapor_mutu.hitung,
                    pakta : getData.rapor_mutu.pakta,
                    verval : getData.rapor_mutu.verval,
                    proses : getData.rapor_mutu.proses,
                    terima : getData.rapor_mutu.terima,
                    tolak : getData.rapor_mutu.tolak,
                }
                let tempBintangKomponen = {};
                let tempBintangAspek = {};
                let tempBintangInstrumen = {};
                $.each(getData.data, function(komponen_id, komponen) {
                    tempBintangKomponen[komponen.id] = (komponen.nilai_komponen) ? komponen.nilai_komponen.total_nilai : 0; 
                    $.each(komponen.aspek, function(aspek_id, aspek) {
                        tempBintangAspek[aspek.id] = (aspek.nilai_aspek) ? aspek.nilai_aspek.total_nilai : 0;
                        $.each(aspek.instrumen, function(key, instrumen) {
                            tempBintangInstrumen[instrumen.instrumen_id] = (instrumen.nilai_instrumen) ? instrumen.nilai_instrumen.nilai : 0; 
                        });
                    });
                });
                this.bintangKomponen = tempBintangKomponen
                this.bintangAspek = tempBintangAspek
                this.bintangInstrumen = tempBintangInstrumen
                this.kuisioners = getData.data
                this.output_indikator = getData.output_indikator
                this.rapor.kuisioner.lengkap = (getData.rapor.jml_instrumen == getData.detil_user.nilai_instrumen_count)
                this.rapor.kuisioner.tgl = (getData.rapor.kuisioner) ? getData.rapor.kuisioner : '-'
                this.rapor.hitung.lengkap = getData.rapor.hitung
                this.rapor.hitung.tgl = (getData.rapor.hitung) ? getData.rapor.hitung : '-'
                this.rapor.pakta.lengkap = (getData.rapor.pakta_integritas) ? true : false
                this.rapor.pakta.tgl = (getData.rapor.pakta_integritas) ? getData.rapor.pakta_integritas : '-'
                this.rapor.verval.lengkap = getData.rapor.verval
                this.rapor.verval.tgl = (getData.rapor.verval) ? getData.rapor.verval : '-'
                this.rapor.proses.lengkap = getData.rapor.proses
                this.rapor.proses.tgl = (getData.rapor.proses) ? getData.rapor.proses : '-'
                this.rapor.terima.lengkap = getData.rapor.terima
                this.rapor.terima.tgl = (getData.rapor.terima) ? getData.rapor.terima : '-'
                this.rapor.tolak.lengkap = getData.rapor.tolak
                this.rapor.tolak.tgl = (getData.rapor.tolak) ? getData.rapor.tolak : '-'
                this.rapor.verifikator_id = (getData.detil_user.sekolah.sekolah_sasaran) ? getData.detil_user.sekolah.sekolah_sasaran.verifikator_id : null
                this.nama_sekolah = getData.detil_user.name
                this.nilai_rapor_mutu = (getData.detil_user.nilai_akhir) ? getData.detil_user.nilai_akhir.nilai : 0
                this.predikat_sekolah = (getData.detil_user.nilai_akhir) ? getData.detil_user.nilai_akhir.predikat : ''
                let DataKeterangan = [];
                let vm = this
                $.each(getData.rapor_mutu.nilai_rapor_mutu.labels, function(key, valua) {
                    vm.id_komponen[key] = valua
                    DataKeterangan[key] = {
                        komponen: valua,
                        tercapai: getData.rapor_mutu.nilai_rapor_mutu.nilai_tercapai[key],
                        belum_tercapai: getData.rapor_mutu.nilai_rapor_mutu.nilai_belum_tercapai[key],
                        total: parseFloat(getData.rapor_mutu.nilai_rapor_mutu.nilai_tercapai[key]) + parseFloat(getData.rapor_mutu.nilai_rapor_mutu.nilai_belum_tercapai[key]),
                    }
                })
                vm.createChart('chartdiv', DataKeterangan)
            });
        },
        cetak_rapor_mutu(data){
            this.show_spinner_cetak = true
            this.show_text_cetak = false
            axios.get(`/api/rapor-mutu/cetak-rapor`, {
                params : {
                    user_id: data.user_id,
                    sekolah_id : data.sekolah.sekolah_id,
                    sekolah_sasaran_id: data.sekolah.sekolah_sasaran.sekolah_sasaran_id
                },
                responseType: 'arraybuffer'
            }).then((response) => {
                this.show_text_cetak = true
                this.show_spinner_cetak = false
                return false;
                let blob = new Blob([response.data], { type: 'application/pdf' })
                let link = document.createElement('a')
                link.href = window.URL.createObjectURL(blob)
                link.download = 'Pakta Integritas Penjaminan Mutu SMK.pdf'
                link.click()
                this.show_text_cetak = true
                this.show_spinner_cetak = false
            })
        },
        hitung_rapor_mutu: function (event) {
            Swal.fire({
                title: 'Apakah Anda Yakin?',
                text: "Proses ini akan sedikit memakan waktu!",
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya!',
                cancelButtonText: 'Batal',
                showLoaderOnConfirm: true,
            }).then((result) => {
                if (result.value) {
                    axios.post(`/api/hitung-nilai-instrumen`, {
                        user_id: user.user_id,
                        verifikator_id: this.rapor.verifikator_id
                    }).then((response) => {
                        Swal.fire(
                            'Selesai',
                            'Hitung Nilai Instrumen Berhasil!',
                            'success'
                        ).then(() => {
                            this.loadPostsData();
                        })
                    }).catch((data)=> {
                        Swal.fire("Gagal!", data.message, "warning");
                    })
                }
            })
        },
    }
}

</script>
