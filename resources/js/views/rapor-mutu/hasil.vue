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
                                        <li class="li" v-bind:class="{ complete: rapor_mutu.verifikasi }">
                                            <div class="timestamp">
                                                <span class="date">{{(rapor_mutu.verifikasi) ? rapor_mutu.verifikasi : '-'}}</span>
                                            </div>
                                            <div class="status">
                                                <h4 class="khusus_timeline text-center" v-bind:class="{ checklist: rapor_mutu.verifikasi }"> Verifikasi oleh Direktorat </h4>
                                            </div>
                                        </li>
                                        <li class="li" v-bind:class="{ complete: rapor_mutu.pengesahan }">
                                            <div class="timestamp">
                                                <span class="date">{{(rapor_mutu.pengesahan) ? rapor_mutu.pengesahan : '-'}}</span>
                                            </div>
                                            <div class="status">
                                                <h4 class="khusus_timeline text-center" v-bind:class="{ checklist: rapor_mutu.pengesahan }"> Pengesahan oleh Direktorat </h4>
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
        <my-loader/>
    </div>
</template>
<style lang="scss" scoped>
  @import './../../../../public/css/timeline_simple.scss'; /* injected */
</style>
<script>

import axios from 'axios' //IMPORT AXIOS
export default {
    created() {
        this.loadPostsData()
    },
    data() {
        return {
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
                verifikasi : {
                    lengkap: 0,
                     tgl : '-'
                },
                pengesahan : {
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
        loadPostsData() {
            axios.post(`/api/rapor-mutu/hasil`, {
                user_id: user.user_id,
            }).then((response) => {
                let getData = response.data
                this.rapor_mutu = {
                    instrumen : getData.rapor_mutu.instrumen,
                    hitung : getData.rapor_mutu.hitung,
                    pakta : getData.rapor_mutu.pakta,
                    verval : getData.rapor_mutu.verval,
                    verifikasi : getData.rapor_mutu.verifikasi,
                    pengesahan : getData.rapor_mutu.pengesahan,
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
                this.rapor.verifikasi.lengkap = getData.rapor.verifikasi
                this.rapor.verifikasi.tgl = (getData.rapor.verifikasi) ? getData.rapor.verifikasi : '-'
                this.rapor.pengesahan.lengkap = getData.rapor.pengesahan
                this.rapor.pengesahan.tgl = (getData.rapor.pengesahan) ? getData.rapor.pengesahan : '-'
                this.rapor.verifikator_id = (getData.detil_user.sekolah.sekolah_sasaran) ? getData.detil_user.sekolah.sekolah_sasaran.verifikator_id : null
                this.nama_sekolah = getData.detil_user.name
                this.nilai_rapor_mutu = (getData.detil_user.nilai_akhir) ? getData.detil_user.nilai_akhir.nilai : 0
                this.predikat_sekolah = (getData.detil_user.nilai_akhir) ? getData.detil_user.nilai_akhir.predikat : ''
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
                    axios.post(`/api/hitung-nilai-instrumen`, {
                        user_id: user.user_id,
                        verifikator_id: this.rapor.verifikator_id
                    }).then((response) => {
                        console.log(response)
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
