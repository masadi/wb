<template>
<div>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Informasi Mutu Standar Nasional Pendidikan (SNP)</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <router-link tag="a" to="/beranda">Beranda</router-link>
                        </li>
                        <li class="breadcrumb-item active">Informasi Mutu Standar Nasional Pendidikan (SNP)</li>
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
                        <div class="card-body" v-show="!is_coe">
                            <p>{{no_coe}}</p>
                        </div>
                        <div class="card-body" v-show="is_coe">
                            <div class="d-flex justify-content-center">
                                <button type="button" class="btn btn-primary btn-lg btn-flat mb-3" :disabled='diMatikan' v-on:click="hitung_rapor_mutu"><span class="h4"><i class="fas fa-clipboard-check"></i> HITUNG INFORMASI MUTU SNP</span></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" v-show="is_coe">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-4">
                                    <div id="chartRadar" style="width: 100%;height: 500px;"></div>
                                </div>
                                <div class="col-8">
                                    <div class="chartdiv" id="chartdiv" ref="chartdiv" style="width: 100%;height: 500px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" v-show="is_coe">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center" scope="col">Nama Sekolah</th>
                                        <th class="text-center" scope="col">NPSN</th>
                                        <th class="text-center" scope="col">Kab/Kota</th>
                                        <th class="text-center" scope="col">Provinsi</th>
                                        <th class="text-center" scope="col">Capaian SNP</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-center">{{nama_sekolah}}</td>
                                        <td class="text-center">{{npsn}}</td>
                                        <td class="text-center">{{kab_kota}}</td>
                                        <td class="text-center">{{provinsi}}</td>
                                        <td class="text-center">{{capaian_snp}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center" scope="col">No</th>
                                        <th class="text-center" scope="col">Standar</th>
                                        <th class="text-center" scope="col">Nilai</th>
                                        <th class="text-center" scope="col">Predikat</th>
                                        <th class="text-center" scope="col">Kategori</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(kuisioner, key) in kuisioners">
                                        <td class="text-center">{{key + 1}}</td>
                                        <td><span @click="batang(kuisioner.id)">{{kuisioner.nama}}</span></td>
                                        <td class="text-center">{{(kuisioner.nilai_akhir) ? kuisioner.nilai_akhir.nilai : 0}}</td>
                                        <td class="text-center">{{(kuisioner.nilai_akhir) ? kuisioner.nilai_akhir.predikat : '-'}}</td>
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
                    <div class="card">
                        <div class="card-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="text-center" scope="col" colspan="4">No</th>
                                        <th class="text-center" scope="col">Standar/Instrumen/Breakdown</th>
                                        <th class="text-center" scope="col">Nilai</th>
                                        <th class="text-center" scope="col">Predikat</th>
                                        <th class="text-center" scope="col">Kategori</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template v-for="(kuisioner, key) in kuisioners">
                                        <tr class="bg-success">
                                            <td class="text-right">{{key+1}}</td>
                                            <td colspan="4">{{kuisioner.nama}}</td>
                                            <td class="text-center">{{(kuisioner.nilai_akhir) ? kuisioner.nilai_akhir.nilai : 0}}</td>
                                            <td class="text-center">{{(kuisioner.nilai_akhir) ? kuisioner.nilai_akhir.predikat : '-'}}</td>
                                            <td class="text-center">
                                                <span class="fa fa-star" v-bind:class="{'bintang': bintangKomponen[kuisioner.id] >= 1}"></span>
                                                <span class="fa fa-star" v-bind:class="{'bintang': bintangKomponen[kuisioner.id] >= 21}"></span>
                                                <span class="fa fa-star" v-bind:class="{'bintang': bintangKomponen[kuisioner.id] >= 41}"></span>
                                                <span class="fa fa-star" v-bind:class="{'bintang': bintangKomponen[kuisioner.id] >= 61}"></span>
                                                <span class="fa fa-star" v-bind:class="{'bintang': bintangKomponen[kuisioner.id] >= 81}"></span>
                                            </td>
                                        </tr>
                                        <template v-for="(instrumen, sub_key) in kuisioner.instrumen_standar">
                                            <tr>
                                                <td class="text-right"></td>
                                                <td class="text-right">{{key+1}}.{{sub_key+1}}</td>
                                                <td colspan="3">{{instrumen.instrumen.pertanyaan}}</td>
                                                <td class="text-center">{{(instrumen.instrumen.nilai_instrumen) ? instrumen.instrumen.nilai_instrumen.nilai : 0}}</td>
                                                <td class="text-center">{{(instrumen.instrumen.nilai_instrumen) ? instrumen.instrumen.nilai_instrumen.predikat : '-'}}</td>
                                                <td class="text-center">
                                                    <span class="fa fa-star" v-bind:class="{'bintang': bintangInstrumen[instrumen.instrumen.instrumen_id] >= 1}"></span>
                                                    <span class="fa fa-star" v-bind:class="{'bintang': bintangInstrumen[instrumen.instrumen.instrumen_id] >= 2}"></span>
                                                    <span class="fa fa-star" v-bind:class="{'bintang': bintangInstrumen[instrumen.instrumen.instrumen_id] >= 3}"></span>
                                                    <span class="fa fa-star" v-bind:class="{'bintang': bintangInstrumen[instrumen.instrumen.instrumen_id] >= 4}"></span>
                                                    <span class="fa fa-star" v-bind:class="{'bintang': bintangInstrumen[instrumen.instrumen.instrumen_id] >= 5}"></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="5"></td>
                                                <td colspan="3" class="text-center">Capaian</td>
                                            </tr>
                                            <template v-for="(breakdown, sub_sub_key) in instrumen.instrumen.breakdown">
                                                <tr>
                                                    <td class="text-right"></td>
                                                    <td class="text-right"></td>
                                                    <td style="vertical-align:middle;" class="text-right">{{key+1}}.{{sub_key+1}}.{{sub_sub_key+1}}</td>
                                                    <td colspan="2">{{breakdown.breakdown}}</td>
                                                    <td class="text-right"></td>
                                                    <td class="text-right"></td>
                                                    <td class="text-right"></td>
                                                </tr>
                                                <template v-for="(question, sub_sub_sub_key) in breakdown.question">
                                                    <tr>
                                                        <td class="text-right"></td>
                                                        <td class="text-right"></td>
                                                        <td class="text-right"></td>
                                                        <td style="vertical-align:middle;" class="text-right">{{key+1}}.{{sub_key+1}}.{{sub_sub_key+1}}.{{sub_sub_sub_key+1}}</td>
                                                        <td>{{question.question}}</td>
                                                        <template v-if="question.answer.length == 1">
                                                            <td v-for="(answer, sub_sub_sub_sub_key) in question.answer" class="text-center">{{(answer.nilai_answer) ? answer.nilai_answer.answer : '-'}}</td>
                                                            <td class="text-center">-</td>
                                                            <td class="text-center">-</td>
                                                        </template>
                                                        <template v-else>
                                                            <td v-for="(answer, sub_sub_sub_sub_key) in question.answer" class="text-center">{{(answer.nilai_answer) ? answer.nilai_answer.answer : '-'}}</td>
                                                        </template>
                                                    </tr>
                                                </template>
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
    <my-loader />
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
@import './../../../../public/css/timeline_simple.scss';
/* injected */
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
            is_coe: null,
            no_coe: 'Loading....',
            all_komponen: [1, 2, 3, 4, 5, 6, 7, 8],
            id_komponen: [],
            user: user,
            pakta_integritas: 0,
            nilai_standar: 0,
            sekolah_id: user.sekolah_id,
            kuisioners: [],
            output_indikator: [],
            bintangKomponen: {},
            bintangAspek: {},
            bintangInstrumen: {},
            nama_sekolah: '',
            npsn: '',
            kab_kota: '',
            provinsi: '',
            capaian_snp: '',
            nilai_rapor_mutu: 0,
            nilai_rapor_mutu_verifikasi: 0,
            predikat_sekolah: '-',
            predikat_sekolah_verifikasi: '-',
            data_lengkap: null,
            show_spinner_cetak: false,
            show_text_cetak: true,
            chartData: [],
            isi_batang: null,
        }
    },
    components: {
        'batang': Batang,
    },
    beforeDestroy() {
        if (this.chart) {
            this.chart.dispose();
        }
    },
    computed: {
        diMatikan() {
            if (this.nilai_standar) {
                return true
            } else if (this.pakta_integritas) {
                return true
            } else {
                return false
            }
        }
    },
    methods: {
        batang(id) {
            this.isi_batang = id
            this.$refs['my-modal'].show()
        },
        createChart(chartID, chartData) {
            if (chartData) {
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
                    //categoryAxis.renderer.labels.template.rotation = 45;

                    var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
                    valueAxis.title.text = "Ketercapaian Standar";
                    valueAxis.min = 0;
                    valueAxis.max = 100;
                    valueAxis.strictMinMax = true;
                    valueAxis.renderer.labels.template.adapter.add("text", function (text) {
                        return text;
                    });
                    // Create series
                    var series = chart.series.push(new am4charts.ColumnSeries3D());
                    series.dataFields.valueY = "tercapai";
                    series.dataFields.categoryX = "komponen";
                    series.name = "Standar Tercapai";
                    series.clustered = false;
                    series.columns.template.tooltipHTML = "<center>Standar tercapai: <br> <strong>{valueY}</strong></center>";
                    series.columns.template.fillOpacity = 0.9;
                    series.columns.template.showTooltipOn = "always";
                    series.tooltip.pointerOrientation = "down";
                    series.columns.template.width = am4core.percent(50);
                    chart.exporting.menu = new am4core.ExportMenu();
                })();
            }
        },
        createChartRadial(chartID, chartData) {
            if (chartData) {
                (function () {
                    var chart = am4core.create(
                        document.getElementById(chartID),
                        am4charts.RadarChart
                    );
                    let data = [];
                    let value1 = 500;
                    let value2 = 600;

                    for(var i = 0; i < 12; i++){
                    let date = new Date();
                    date.setMonth(i, 1);
                    value1 -= Math.round((Math.random() < 0.5 ? 1 : -1) * Math.random() * 50);
                    value2 -= Math.round((Math.random() < 0.5 ? 1 : -1) * Math.random() * 50);
                    data.push({date:date, value1:value1, value2:value2})
                    }

                    chart.data = chartData;

                    /* Create axes */
                    let categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
                    categoryAxis.dataFields.category = "standar";
                    let valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
                    valueAxis.extraMin = 0.2;
                    valueAxis.extraMax = 0.2;
                    valueAxis.tooltip.disabled = true;
                    

                    /* Create and configure series */
                    let series1 = chart.series.push(new am4charts.RadarSeries());
                    series1.dataFields.valueY = "tercapai";
                    series1.dataFields.categoryX = "standar";
                    series1.strokeWidth = 3;
                    series1.tooltipText = "{valueY}";
                    series1.name = "Rapor SNP";
                    series1.bullets.create(am4charts.CircleBullet);
                    series1.dataItems.template.locations.dateX = 0.5;
                    chart.cursor = new am4charts.RadarCursor();
                    chart.legend = new am4charts.Legend();
                })();
            }
        },
        loadPostsData() {
            axios.post(`/api/rapor-mutu/snp`, {
                user_id: user.user_id,
            }).then((response) => {
                let getData = response.data
                let DataKeterangan = [];
                let vm = this
                let tempBintangKomponen = {}
                let tempBintangInstrumen = {};
                var tempCapaian=0;
                $.each(getData.data, function (key, valua) {
                    tempBintangKomponen[valua.id] = (valua.nilai_akhir) ? valua.nilai_akhir.nilai : 0;
                    vm.id_komponen[key] = valua
                    DataKeterangan[key] = {
                        komponen: valua.kode,
                        standar: valua.nama.replace('Standar',''),
                        tercapai: (valua.nilai_akhir) ? valua.nilai_akhir.nilai : 0,
                    }
                    $.each(valua.instrumen_standar, function (a, b) {
                        tempBintangInstrumen[b.instrumen_id] = (b.instrumen.nilai_instrumen) ? b.instrumen.nilai_instrumen.nilai : 0;
                    })
                    tempCapaian+= (valua.nilai_akhir) ? Number(valua.nilai_akhir.nilai) : 0
                })
                vm.createChart('chartdiv', DataKeterangan)
                vm.createChartRadial('chartRadar', DataKeterangan)
                this.no_coe = 'Penjaminan Mutu Tahun 2021 belum dibuka'//'Sekolah Anda belum ditetapkan sebagai SMK Center of Excelent'
                this.is_coe = true//(getData.detil_user.sekolah) ? getData.detil_user.sekolah.smk_coe : null
                this.nilai_standar = (getData.detil_user.nilai_standar) ? false : true
                this.pakta_integritas = (getData.detil_user.sekolah.sekolah_sasaran) ? getData.detil_user.sekolah.sekolah_sasaran.pakta_integritas : null
                this.kuisioners = getData.data
                this.bintangKomponen = tempBintangKomponen
                this.bintangInstrumen = tempBintangInstrumen
                this.nama_sekolah = getData.detil_user.name
                this.npsn = getData.detil_user.username
                this.kab_kota = getData.detil_user.sekolah.kabupaten
                this.provinsi = getData.detil_user.sekolah.provinsi
                this.capaian_snp = (tempCapaian / 7) +'%'
                console.log(tempCapaian / 7);
                return false
                this.data_lengkap = getData.detil_user
                this.rapor_mutu = {
                    instrumen: getData.rapor_mutu.instrumen,
                    hitung: getData.rapor_mutu.hitung,
                    pakta: getData.rapor_mutu.pakta,
                    verval: getData.rapor_mutu.verval,
                    proses: getData.rapor_mutu.proses,
                    terima: getData.rapor_mutu.terima,
                    tolak: getData.rapor_mutu.tolak,
                }
                
                let tempBintangAspek = {};
                
                $.each(getData.data, function (komponen_id, komponen) {
                    tempBintangKomponen[komponen.id] = (komponen.nilai_komponen) ? komponen.nilai_komponen.total_nilai : 0;
                    $.each(komponen.aspek, function (aspek_id, aspek) {
                        tempBintangAspek[aspek.id] = (aspek.nilai_aspek) ? aspek.nilai_aspek.total_nilai : 0;
                        $.each(aspek.instrumen, function (key, instrumen) {
                            tempBintangInstrumen[instrumen.instrumen_id] = (instrumen.nilai_instrumen) ? instrumen.nilai_instrumen.nilai : 0;
                        });
                    });
                });
                this.bintangAspek = tempBintangAspek
                this.output_indikator = getData.output_indikator
                this.rapor.kuisioner.lengkap = getData.rapor_mutu.instrumen
                this.rapor.pakta.lengkap = getData.rapor_mutu.pakta
                this.rapor.verifikator_id = (getData.detil_user.sekolah.sekolah_sasaran) ? getData.detil_user.sekolah.sekolah_sasaran.verifikator_id : null
                /*this.rapor.kuisioner.lengkap = (getData.rapor.jml_instrumen == getData.detil_user.nilai_instrumen_count)
                this.rapor.kuisioner.tgl = (getData.rapor.kuisioner) ? getData.rapor.kuisioner : '-'
                this.rapor.hitung.lengkap = getData.rapor.hitung
                this.rapor.hitung.tgl = (getData.rapor.hitung) ? getData.rapor.hitung : '-'
                this.rapor.pakta.lengkap = getData.rapor.pakta_integritas
                this.rapor.pakta.tgl = (getData.rapor.pakta_integritas) ? getData.rapor.pakta_integritas : '-'
                this.rapor.verval.lengkap = getData.rapor.verval
                this.rapor.verval.tgl = (getData.rapor.verval) ? getData.rapor.verval : '-'
                this.rapor.proses.lengkap = getData.rapor.proses
                this.rapor.proses.tgl = (getData.rapor.proses) ? getData.rapor.proses : '-'
                this.rapor.terima.lengkap = getData.rapor.terima
                this.rapor.terima.tgl = (getData.rapor.terima) ? getData.rapor.terima : '-'
                this.rapor.tolak.lengkap = getData.rapor.tolak
                this.rapor.tolak.tgl = (getData.rapor.tolak) ? getData.rapor.tolak : '-'
                this.rapor.verifikator_id = (getData.detil_user.sekolah.sekolah_sasaran) ? getData.detil_user.sekolah.sekolah_sasaran.verifikator_id : null*/
                
                this.nilai_rapor_mutu = (getData.detil_user.nilai_akhir) ? getData.detil_user.nilai_akhir.nilai : 0
                this.predikat_sekolah = (getData.detil_user.nilai_akhir) ? getData.detil_user.nilai_akhir.predikat : '-'
                if (getData.detil_user.sekolah.npsn === '20219159') {
                    this.nilai_rapor_mutu_verifikasi = '-'
                    this.predikat_sekolah_verifikasi = '-'
                    this.rapor_mutu.verval = null
                } else {
                    this.nilai_rapor_mutu_verifikasi = (getData.detil_user.nilai_akhir_verifikasi) ? getData.detil_user.nilai_akhir_verifikasi.nilai : '-'
                    this.predikat_sekolah_verifikasi = (getData.detil_user.nilai_akhir_verifikasi) ? getData.detil_user.nilai_akhir_verifikasi.predikat : '-'
                }
            });
        },
        cetak_rapor_mutu(data) {
            this.show_spinner_cetak = true
            this.show_text_cetak = false
            axios.get(`/api/rapor-mutu/cetak-rapor`, {
                params: {
                    user_id: data.user_id,
                    sekolah_id: data.sekolah.sekolah_id,
                    sekolah_sasaran_id: data.sekolah.sekolah_sasaran.sekolah_sasaran_id
                },
                responseType: 'arraybuffer'
            }).then((response) => {
                this.show_text_cetak = true
                this.show_spinner_cetak = false
                return false;
                let blob = new Blob([response.data], {
                    type: 'application/pdf'
                })
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
                    axios.post(`/api/hitung-rapor/snp`, {
                        user_id: user.user_id,
                    }).then((response) => {
                        Swal.fire(
                            'Selesai',
                            'Hitung Rapor Mutu SNP Berhasil!',
                            'success'
                        ).then(() => {
                            this.loadPostsData();
                        })
                    }).catch((data) => {
                        Swal.fire("Gagal!", data.message, "warning");
                    })
                }
            })
        },
    }
}
</script>
