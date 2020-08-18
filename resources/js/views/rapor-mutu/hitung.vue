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
        }
    },
    methods: {
        loadPostsData() {
        }
    }
}

</script>
