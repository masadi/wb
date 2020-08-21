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
                        <div class="card">
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
        data() {
            return {
                terms: false,
                nama_sekolah : null,
                tahun_pendataan: null,
                isBatal : true,
                isCheckbox : false,
                isShow : false,
            }
        },
        computed: {
            isDisabled: function(){
                return !this.terms;
            }
        },
        created() {
            //this.loadPostsData()
        },
        methods: {
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
                axios.get(`/api/rapor-mutu/pakta`, {
                    params : {
                        user_id: user.user_id,
                    }
                })
                .then((response) => {
                    let getData = response.data
                    this.nama_sekolah = getData.user.name
                    this.tahun_pendataan = getData.tahun_pendataan.tahun_pendataan_id
                    this.tanggal = (getData.user.sekolah.pakta_integritas) ? getData.user.sekolah.pakta_integritas.created_at : '-'
                    this.isBatal = (getData.user.sekolah.pakta_integritas) ? false : true
                    if(getData.instrumen == getData.user.nilai_instrumen_count){
                        this.isCheckbox = (getData.user.sekolah.pakta_integritas) ? true : false
                    } else {
                        this.isCheckbox = true
                    }
                    this.terms = false
                })
            }
        }
    }
</script>
