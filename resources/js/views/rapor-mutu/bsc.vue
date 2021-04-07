<template>
<div>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Rapor Mutu Balance Score Card</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <router-link tag="a" to="/beranda">Beranda</router-link>
                        </li>
                        <li class="breadcrumb-item active">Rapor Mutu Balance Score Card</li>
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
                                <button type="button" class="btn btn-primary btn-lg btn-flat mb-3" :disabled='diMatikan' v-on:click="hitung_rapor_mutu"><span class="h4"><i class="fas fa-clipboard-check"></i> HITUNG RAPOR MUTU BALANCE SCORE CARD</span></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" v-show="is_coe">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="chartdiv" id="chartdiv" ref="chartdiv" style="width: 100%;height: 700px;"></div>
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
                                        <th class="text-center" scope="col">No</th>
                                        <th class="text-center" scope="col">Dimensi</th>
                                        <th class="text-center" scope="col">Indikator Level 1</th>
                                        <th class="text-center" scope="col">No L2</th>
                                        <th class="text-center" scope="col">Indikator Level 2</th>
                                        <th class="text-center" scope="col">Hasil Perhitungan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template v-for="(data, key) in all_data">
                                        <template v-for="(data_a, key_a) in data.sub_isi_standar">
                                            <template v-for="(data_b, key_b) in data_a.sub_isi_standar">
                                                <tr>
                                                    <td class="text-center">{{data.kode}}</td>
                                                    <td>{{data.nama}}</td>
                                                    <td>{{data_a.kode}} {{data_a.nama}}</td>
                                                    <td class="text-center">{{data_b.kode}}</td>
                                                    <td>{{data_b.nama}}</td>
                                                    <td class="text-center">?</td>
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
            isi_batang: null,
            id_komponen: [],
            all_data: [],
            /*all_komponen: [1, 2, 3, 4, 5, 6, 7, 8],
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
            nilai_rapor_mutu: 0,
            nilai_rapor_mutu_verifikasi: 0,
            predikat_sekolah: '-',
            predikat_sekolah_verifikasi: '-',
            data_lengkap: null,
            show_spinner_cetak: false,
            show_text_cetak: true,
            chartData: [],
            */
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
                    /*series.columns.template.events.on("hit", function(ev) {
                        console.log("clicked on ", ev.target);
                    }, this);*/
                    chart.exporting.menu = new am4core.ExportMenu();
                })();
            }
        },
        loadPostsData() {
            axios.post(`/api/rapor-mutu/bsc`, {
                user_id: user.user_id,
            }).then((response) => {
                let getData = response.data
                this.all_data = getData.data
                let DataKeterangan = [];
                let vm = this
                $.each(getData.data, function (key, valua) {
                    vm.id_komponen[key] = valua
                    DataKeterangan[key] = {
                        komponen: valua.kode,
                        tercapai: (valua.nilai_akhir) ? valua.nilai_akhir.nilai : 0,
                        //belum_tercapai: getData.rapor_mutu.nilai_rapor_mutu.nilai_belum_tercapai[key],
                        //total: parseFloat(getData.rapor_mutu.nilai_rapor_mutu.nilai_tercapai[key]) + parseFloat(getData.rapor_mutu.nilai_rapor_mutu.nilai_belum_tercapai[key]),
                    }
                })
                //console.log(DataKeterangan);
                //console.log(getData.detil_user);
                vm.createChart('chartdiv', DataKeterangan)
                this.no_coe = 'Penjaminan Mutu Tahun 2021 belum dibuka'//'Sekolah Anda belum ditetapkan sebagai SMK Center of Excelent'
                this.is_coe = true//(getData.detil_user.sekolah) ? getData.detil_user.sekolah.smk_coe : null
                this.nilai_standar = (getData.detil_user.nilai_standar) ? false : true
                this.pakta_integritas = (getData.detil_user.sekolah.sekolah_sasaran) ? getData.detil_user.sekolah.sekolah_sasaran.pakta_integritas : null
                return false
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
                    axios.post(`/api/hitung-rapor/bsc`, {
                        user_id: user.user_id,
                    }).then((response) => {
                        Swal.fire(
                            'Selesai',
                            'Hitung Rapor Mutu Basic Score Card Berhasil!',
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
