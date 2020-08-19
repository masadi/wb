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
                                    <button type="button" class="btn btn-primary btn-lg btn-flat mb-3"><span class="h4"><i class="fas fa-clipboard-check"></i> HITUNG RAPOR MUTU SEKOLAH</span></button>
                                </div>
                                <div class="row">
                                    <ul class="timeline" id="timeline">
                                        <li class="li" v-bind:class="{ complete: step.kuisioner.lengkap }">
                                            <div class="timestamp">
                                                <span class="date">{{step.kuisioner.tgl}}</span>
                                            </div>
                                            <div class="status">
                                                <h4 class="khusus_timeline text-center" v-bind:class="{ checklist: step.kuisioner.lengkap }"> Mengisi Kuisioner </h4>
                                            </div>
                                        </li>
                                        <li class="li" v-bind:class="{ complete: step.hitung.lengkap }">
                                            <div class="timestamp">
                                                <span class="date">{{step.hitung.tgl}}</span>
                                            </div>
                                            <div class="status">
                                                <h4 class="khusus_timeline text-center" v-bind:class="{ checklist: step.hitung.lengkap }"> Mengitung Rapor Mutu Sekolah </h4>
                                            </div>
                                        </li>
                                        <li class="li" v-bind:class="{ complete: step.kirim.lengkap }">
                                            <div class="timestamp">
                                                <span class="date">{{step.kirim.tgl}}</span>
                                            </div>
                                            <div class="status">
                                                <h4 class="khusus_timeline text-center" v-bind:class="{ checklist: step.kirim.lengkap }"> Kirim Hasil Rapor Mutu Sekolah </h4>
                                            </div>
                                        </li>
                                        <li class="li" v-bind:class="{ complete: step.verval.lengkap }">
                                            <div class="timestamp">
                                                <span class="date">{{step.verval.tgl}}</span>
                                            </div>
                                            <div class="status">
                                                <h4 class="khusus_timeline text-center" v-bind:class="{ checklist: step.verval.lengkap }"> Verval oleh Verifikator </h4>
                                            </div>
                                        </li>
                                        <li class="li" v-bind:class="{ complete: step.verifikasi.lengkap }">
                                            <div class="timestamp">
                                                <span class="date">{{step.verifikasi.tgl}}</span>
                                            </div>
                                            <div class="status">
                                                <h4 class="khusus_timeline text-center" v-bind:class="{ checklist: step.verifikasi.lengkap }"> Verifikasi oleh Direktorat </h4>
                                            </div>
                                        </li>
                                        <li class="li" v-bind:class="{ complete: step.pengesahan.lengkap }">
                                            <div class="timestamp">
                                                <span class="date">{{step.pengesahan.tgl}}</span>
                                            </div>
                                            <div class="status">
                                                <h4 class="khusus_timeline text-center" v-bind:class="{ checklist: step.pengesahan.lengkap }"> Pengesahan oleh Direktorat </h4>
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
                                                <span class="fa fa-star" v-bind:class="{'bintang': getBintang[kuisioner.id] >= 1}"></span>
                                                <span class="fa fa-star" v-bind:class="{'bintang': getBintang[kuisioner.id] >= 21}"></span>
                                                <span class="fa fa-star" v-bind:class="{'bintang': getBintang[kuisioner.id] >= 41}"></span>
                                                <span class="fa fa-star" v-bind:class="{'bintang': getBintang[kuisioner.id] >= 61}"></span>
                                                <span class="fa fa-star" v-bind:class="{'bintang': getBintang[kuisioner.id] >= 81}"></span>
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
                                <div class="d-flex border-bottom">
                                    <div class="mr-auto py-2">Komponen / Aspek / Indikator</div>
                                    <div class="py-2 pr-4">Nilai</div>
                                    <div class="py-2 pl-4 pr-4">Predikat</div>
                                    <div class="py-2 pl-4">Kategori</div>
                                </div>
                                <div v-for="(indikator, key) in output_indikator" class="d-flex border-bottom">
                                    <div class="mr-auto py-2">{{key + 1}}. {{indikator.atribut.aspek.komponen.nama}}</div>
                                    <div class="py-2 pr-4">Nilai</div>
                                    <div class="py-2 pl-4 pr-4">Predikat</div>
                                    <div class="py-2 pl-4">Kategori</div>
                                </div>
                                <!--table class="table">
                                    <thead>
                                        <tr>
                                            <th class="text-center" scope="col">Komponen / Aspek / Indikator</th>
                                            <th class="text-center" scope="col">Nilai</th>
                                            <th class="text-center" scope="col">Predikat</th>
                                            <th class="text-center" scope="col">Kategori</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(aspek, key) in output_aspek">
                                            <td>{{aspek.nama}}</td>
                                            <td class="text-center">{{(aspek.nilai_komponen) ? aspek.nilai_komponen.total_nilai : 0}}</td>
                                            <td class="text-center">{{(aspek.nilai_komponen) ? aspek.nilai_komponen.predikat : '-'}}</td>
                                            <td class="text-center">
                                                <span class="fa fa-star" v-bind:class="{'bintang': getBintang[aspek.id] >= 1}"></span>
                                                <span class="fa fa-star" v-bind:class="{'bintang': getBintang[aspek.id] >= 21}"></span>
                                                <span class="fa fa-star" v-bind:class="{'bintang': getBintang[aspek.id] >= 41}"></span>
                                                <span class="fa fa-star" v-bind:class="{'bintang': getBintang[aspek.id] >= 61}"></span>
                                                <span class="fa fa-star" v-bind:class="{'bintang': getBintang[aspek.id] >= 81}"></span>
                                            </td>
                                        </tr>
                                    </tbody>
                                    {{output_indikator}}
                                </table-->
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
            step:{
                kuisioner : {
                    lengkap: 1,
                    tgl : '10/08/2020'
                },
                hitung : {
                    lengkap: 1,
                    tgl : '12/08/2020'
                },
                kirim : {
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
            getBintang: {},
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
                let tempBintang = {};
                $.each(getData.data, function(key, value) {
                    tempBintang[value.id] = (value.nilai_komponen) ? value.nilai_komponen.total_nilai : 0; 
                    $.each(value, function(index, val) {
                        /*tempIndikator[val.instrumen_id] = val.indikator_id; 
                        tempAtribut[val.instrumen_id] = val.indikator.atribut_id; 
                        tempAspek[val.instrumen_id] = val.indikator.atribut.aspek_id; 
                        tempKomponen[val.instrumen_id] = val.indikator.atribut.aspek.komponen_id; 
                        if(val.jawaban){
                            tempData[val.jawaban.instrumen_id] = val.jawaban.nilai; 
                        }*/
                    });
                });
                this.getBintang = tempBintang
                this.kuisioners = getData.data
                this.output_indikator = getData.output_indikator
            });
        }
    }
}

</script>
