<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Raport Mutu Sekolah</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><router-link tag="a" to="/beranda">Beranda</router-link></li>
                            <li class="breadcrumb-item active">Raport Mutu Sekolah</li>
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
                                    <button type="button" class="btn btn-primary btn-lg btn-flat mb-3" :disabled='!rapor.kuisioner.lengkap' v-on:click="hitung_rapor_mutu"><span class="h4"><i class="fas fa-clipboard-check"></i> HITUNG RAPOR MUTU SEKOLAH</span></button>
                                </div>
                                <div class="row">
                                    <ul class="timeline" id="timeline">
                                        <li class="li" v-bind:class="{ complete: rapor.kuisioner.lengkap }">
                                            <div class="timestamp">
                                                <span class="date">{{rapor.kuisioner.tgl}}</span>
                                            </div>
                                            <div class="status">
                                                <h4 class="khusus_timeline text-center" v-bind:class="{ checklist: rapor.kuisioner.lengkap }"> Mengisi Kuisioner </h4>
                                            </div>
                                        </li>
                                        <li class="li" v-bind:class="{ complete: rapor.hitung.lengkap }">
                                            <div class="timestamp">
                                                <span class="date">{{rapor.hitung.tgl}}</span>
                                            </div>
                                            <div class="status">
                                                <h4 class="khusus_timeline text-center" v-bind:class="{ checklist: rapor.hitung.lengkap }"> Mengitung Rapor Mutu Sekolah </h4>
                                            </div>
                                        </li>
                                        <li class="li" v-bind:class="{ complete: rapor.pakta.lengkap }">
                                            <div class="timestamp">
                                                <span class="date">{{rapor.pakta.tgl}}</span>
                                            </div>
                                            <div class="status">
                                                <h4 class="khusus_timeline text-center" v-bind:class="{ checklist: rapor.pakta.lengkap }"> Cetak Pakta Integritas Sekolah </h4>
                                            </div>
                                        </li>
                                        <li class="li" v-bind:class="{ complete: rapor.verval.lengkap }">
                                            <div class="timestamp">
                                                <span class="date">{{rapor.verval.tgl}}</span>
                                            </div>
                                            <div class="status">
                                                <h4 class="khusus_timeline text-center" v-bind:class="{ checklist: rapor.verval.lengkap }"> Verval oleh Verifikator </h4>
                                            </div>
                                        </li>
                                        <li class="li" v-bind:class="{ complete: rapor.verifikasi.lengkap }">
                                            <div class="timestamp">
                                                <span class="date">{{rapor.verifikasi.tgl}}</span>
                                            </div>
                                            <div class="status">
                                                <h4 class="khusus_timeline text-center" v-bind:class="{ checklist: rapor.verifikasi.lengkap }"> Verifikasi oleh Direktorat </h4>
                                            </div>
                                        </li>
                                        <li class="li" v-bind:class="{ complete: rapor.pengesahan.lengkap }">
                                            <div class="timestamp">
                                                <span class="date">{{rapor.pengesahan.tgl}}</span>
                                            </div>
                                            <div class="status">
                                                <h4 class="khusus_timeline text-center" v-bind:class="{ checklist: rapor.pengesahan.lengkap }"> Pengesahan oleh Direktorat </h4>
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
                                            <td>{{kuisioner.nama}}</td>
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
                                            <th class="text-center" scope="col" width="60%" colspan="2">Komponen / Aspek / Indikator</th>
                                            <th class="text-center" scope="col" width="10%">Nilai</th>
                                            <th class="text-center" scope="col" width="15%">Predikat</th>
                                            <th class="text-center" scope="col" width="15%">Kategori</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template v-for="(kuisioner, key) in kuisioners">
                                            <tr>
                                                <td class="text-right">{{key+1}}</td>
                                                <td>{{kuisioner.nama}}</td>
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
                                            <template v-for="(aspek, sub_key) in kuisioner.aspek">
                                                <tr>
                                                    <td class="text-right">{{key+1}}.{{sub_key+1}}</td>
                                                    <td>{{aspek.nama}}</td>
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
    </div>
</template>
<style lang="scss" scoped>
  @import './../../../../public/css/timeline_simple.scss'; /* injected */
</style>
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
                verifikasi : {
                    lengkap: 0,
                     tgl : '-'
                },
                pengesahan : {
                    lengkap: 0,
                     tgl : '-'
                }
            },
            sekolah_id: user.sekolah_id,
            kuisioners: [],
            output_indikator: [],
            bintangKomponen: {},
            bintangAspek: {},
            bintangInstrumen: {},
        }
    },
    methods: {
        loadPostsData() {
            axios.get(`/api/rapor-mutu/hasil`, {
                params: {
                    user_id: user.user_id,
                }
            }).then((response) => {
                let getData = response.data
                let tempBintangKomponen = {};
                let tempBintangAspek = {};
                let tempBintangInstrumen = {};
                $.each(getData.data, function(komponen_id, komponen) {
                    tempBintangKomponen[komponen.id] = (komponen.nilai_komponen) ? komponen.nilai_komponen.total_nilai : 0; 
                    $.each(komponen.aspek, function(aspek_id, aspek) {
                        tempBintangAspek[aspek.id] = (aspek.nilai_aspek) ? (aspek.nilai_aspek.total_nilai * 100 / aspek.bobot) : 0;
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
                this.rapor.pakta.lengkap = getData.rapor.pakta_integritas
                this.rapor.pakta.tgl = (getData.rapor.pakta_integritas) ? getData.rapor.pakta_integritas : '-'
                this.rapor.verval.lengkap = getData.rapor.verval
                this.rapor.verval.tgl = (getData.rapor.verval) ? getData.rapor.verval : '-'
                this.rapor.verifikasi.lengkap = getData.rapor.verifikasi
                this.rapor.verifikasi.tgl = (getData.rapor.verifikasi) ? getData.rapor.verifikasi : '-'
                this.rapor.pengesahan.lengkap = getData.rapor.pengesahan
                this.rapor.pengesahan.tgl = (getData.rapor.pengesahan) ? getData.rapor.pengesahan : '-'
            });
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
                    axios.get(`/api/hitung-nilai-instrumen/${user.user_id}`).then(() => {
                        Swal.fire(
                            'Selesai',
                            'Hitung Nilai Instrumen Berhasil!',
                            'success'
                        ).then(() => {
                            this.loadPostsData();
                        })
                    }).catch((data)=> {
                        Swal.fire("Gagal!", data.message, "warning");
                    });
                }
            })
        },
    }
}

</script>
