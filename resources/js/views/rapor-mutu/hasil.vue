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
                                <!--div class="d-flex border-bottom">
                                    <div class="mr-auto py-2">Komponen / Aspek / Indikator</div>
                                    <div class="py-2 pr-2 pl-2 text-center">Nilai</div>
                                    <div class="py-2 pl-4 pr-4 text-center">Predikat</div>
                                    <div class="py-2 pl-4 text-center">Kategori</div>
                                </div>
                                <div v-for="(kuisioner, key) in kuisioners">
                                    <div class="d-flex border-bottom">
                                        <div class="mr-auto py-2"><strong>{{kuisioner.nama}}</strong></div>
                                        <div class="py-2 pr-2 pl-2 text-center">{{(kuisioner.nilai_komponen) ? kuisioner.nilai_komponen.total_nilai : 0}}</div>
                                        <div class="py-2 pl-4 pr-4 text-center">{{(kuisioner.nilai_komponen) ? kuisioner.nilai_komponen.predikat : '-'}}</div>
                                        <div class="py-2 pl-4 text-center">
                                            <span class="fa fa-star" v-bind:class="{'bintang': getBintang[kuisioner.id] >= 1}"></span>
                                            <span class="fa fa-star" v-bind:class="{'bintang': getBintang[kuisioner.id] >= 21}"></span>
                                            <span class="fa fa-star" v-bind:class="{'bintang': getBintang[kuisioner.id] >= 41}"></span>
                                            <span class="fa fa-star" v-bind:class="{'bintang': getBintang[kuisioner.id] >= 61}"></span>
                                            <span class="fa fa-star" v-bind:class="{'bintang': getBintang[kuisioner.id] >= 81}"></span>
                                        </div>
                                    </div>
                                    <div v-for="(aspek, sub_key) in kuisioner.aspek">
                                        <div class="d-flex border-bottom">
                                            <div class="mr-auto py-2">{{aspek.nama}}</div>
                                            <div class="py-2 pr-2 pl-2 text-center">{{(aspek.nilai_aspek) ? aspek.nilai_aspek.total_nilai : 0}}</div>
                                            <div class="py-2 pl-4 pr-4 text-center">{{(aspek.nilai_aspek) ? aspek.nilai_aspek.predikat : '-'}}</div>
                                            <div class="py-2 pl-4 text-center">
                                                <span class="fa fa-star"></span>
                                                <span class="fa fa-star"></span>
                                                <span class="fa fa-star"></span>
                                                <span class="fa fa-star"></span>
                                                <span class="fa fa-star"></span>
                                            </div>
                                        </div>
                                        <div v-for="(instrumen, sub_sub_key) in aspek.instrumen">
                                            <div class="d-flex border-bottom">
                                                <div class="mr-auto py-2">{{instrumen.pertanyaan}}</div>
                                                <div class="py-2 pr-2 pl-2 text-center">{{(instrumen.nilai_instrumen) ? instrumen.nilai_instrumen.nilai : 0}}</div>
                                                <div class="py-2 pl-4 pr-4 text-center">{{(instrumen.nilai_instrumen) ? instrumen.nilai_instrumen.predikat : '-'}}</div>
                                                <div class="py-2 pl-4 text-center">
                                                    <span class="fa fa-star"></span>
                                                    <span class="fa fa-star"></span>
                                                    <span class="fa fa-star"></span>
                                                    <span class="fa fa-star"></span>
                                                    <span class="fa fa-star"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div-->
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th class="text-center" scope="col" width="65%">Komponen / Aspek / Indikator</th>
                                            <th class="text-center" scope="col" width="10%">Nilai</th>
                                            <th class="text-center" scope="col" width="10%">Predikat</th>
                                            <th class="text-center" scope="col" width="15%">Kategori</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template v-for="(kuisioner, key) in kuisioners">
                                            <tr>
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
            });
        }
    }
}

</script>
