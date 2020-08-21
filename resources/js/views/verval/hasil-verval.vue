<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Hasil Verval Sekolah</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><router-link tag="a" to="/beranda">Beranda</router-link></li>
                            <li class="breadcrumb-item active">Hasil Verval Sekolah</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <form class="form-horizontal" @submit.prevent="insertData()" method="post">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Silahkan pilih Komponen/Aspek/Instrumen yang akan dirubah</h3>
                                </div>
                                <div class="card-body">
                                    <div class="form-group row">
                                        <label for="komponen_id" class="col-sm-2 col-form-label">Sekolah</label>
                                        <div class="col-sm-10">
                                            <v-select :options="form.sekolah" label="text" index="value" @input="getInstrumen" v-model="form.sekolah_id" placeholder="== Pilih Sekolah == "></v-select>
                                        </div>
                                    </div>
                                    <table class="table" v-show="komponen.length > 0">
                                        <thead>
                                            <tr>
                                                <th class="text-center" scope="col">No</th>
                                                <th class="text-center" scope="col">Komponen</th>
                                                <th class="text-center" scope="col">Nilai Asli</th>
                                                <th class="text-center" scope="col">Nilai Perubahan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(value, key) in komponen">
                                                <td class="text-center">{{key + 1}}</td>
                                                <td>{{value.nama}}</td>
                                                <td class="text-center">{{(value.nilai_komponen) ? value.nilai_komponen.total_nilai : 0}}</td>
                                                <td class="text-center">{{(value.nilai_komponen_verifikasi) ? value.nilai_komponen_verifikasi.total_nilai : 0}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="card-footer" v-show="simpan">
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </div>
                            </div>
                        </form>
                        <!--div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Silahkan pilih nama sekolah yang akan ditinjau hasil vervalnya</h3>
                            </div>
                            <div class="card-body">
                                {{isShow}}
                                <div v-show="isShow">
                                    <h3>PERNYATAAN PENGIRIMAN LAPORAN HASIL VERIFIKASI DAN VALIDASI</h3>
                                    <p>Dengan ini Saya sebagai Tim Verifikator menyatakan bahwa data yang diisi oleh sekolah {{nama_sekolah}} pada kuesioner Penjaminan Mutu SMK tahun pendataan {{tahun_pendataan}} telah diperiksa kebenarannya dan telah sesuai dengan fakta yang ada di lapangan.</p>

                                    <p>Saya sepenuhnya siap bertanggung jawab apabila di kemudian hari ditemukan ketidaksesuaian antara data yang diisi di kuesioner Penjaminan Mutu SMK dengan fakta yang ada di lapangan, dan Saya siap menerima sanksi moral, sanksi administrasi, dan sanksi hukum sesuai dengan peraturan dan perundang-undangan yang berlaku.</p>

                                    <p>Tim Verifikator</p>

                                    <p class="text-bold">{{detilUser.name}}</p>
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input :disabled='isCheckbox' class="custom-control-input" type="checkbox" id="terms" v-model='terms'>
                                            <label for="terms" class="custom-control-label">Saya setuju dengan pernyataan di atas</label>
                                        </div>
                                    </div>
                                    <button :disabled='isDisabled' class="btn btn-warning btn-lg btn-flat" v-on:click="cetak_pakta">KIRIM LAPORAN</button>
                                    <button :disabled='isBatal' class="btn btn-danger btn-lg btn-flat" v-on:click="batal_pakta">BATALKAN LAPORAN</button>
                                </div>
                            </div>
                        </div-->
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
        data() {
            return {
                terms: false,
                nama_sekolah : null,
                tahun_pendataan: null,
                isBatal : true,
                isCheckbox : false,
                isShow : false,
                form: new Form({
                    verifikator_id:user.user_id,
                    sekolah_id: '',
                    sekolah: [],
                }),
                simpan:false,
                komponen: [],
            }
        },
        computed: {
            isDisabled: function(){
                return !this.terms;
            }
        },
        created() {
            this.loadPostsData()
        },
        methods: {
            getInstrumen(e){
                if(!e){
                    return false;
                }
                axios.post(`/api/verifikasi/instrumen`, {
                    verifikator_id: user.user_id,
                    sekolah_id: e.value
                })
                .then((response) => {
                    let getData = response.data
                    this.komponen = getData.data
                })
            },
            cetak_pakta : function (event) {
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Jika ada kesalahan laporan hasil verifikasi dan validasi, Anda dapat membatalkan Pernyataan ini sebelum di validasi oleh Tim Direktorat!",
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.value) {
                        axios.get(`/api/rapor-mutu/pra-cetak-pakta`, {
                            params: {
                                user_id: user.user_id,
                            },
                        }).then((response) => {
                            this.loadPostsData()
                            axios.get(`/api/rapor-mutu/cetak-pakta`, {
                                params: {
                                    user_id: user.user_id,
                                },
                                responseType: 'arraybuffer'
                            }).then((response) => {
                                let blob = new Blob([response.data], { type: 'application/pdf' })
                                let link = document.createElement('a')
                                link.href = window.URL.createObjectURL(blob)
                                link.download = 'Pakta Integritas Penjaminan Mutu SMK.pdf'
                                link.click()
                            })
                        })
                    }
                })
            },
            batal_pakta : function (event) {
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Proses pembatalan Pakta Integritas!",
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.value) {
                        axios.get(`/api/rapor-mutu/batal-pakta`, {
                            params : {
                                user_id: user.user_id,
                            }
                        }).then((response) => {
                            Swal.fire(
                                'Berhasil!',
                                'Pakta Integritas dibatalkan',
                                'success'
                            ).then(()=>{
                                this.loadPostsData()
                            });
                        })
                    }
                })
            },
            loadPostsData() {
                /*axios.post(`/api/kuisioner/progres`, {
                    //KIRIMKAN PARAMETER BERUPA PAGE YANG SEDANG DILOAD, PENCARIAN, LOAD PERPAGE DAN SORTING.
                    komponen_id: this.$route.params.id,
                    user_id: user.user_id,
                })*/
                axios.post(`/api/verifikasi/sekolah`, {
                    verifikator_id: user.user_id,
                })
                .then((response) => {
                    let getData = response.data
                    this.form.sekolah = getData.result
                })
            }
        }
    }
</script>
