<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Pakta Integritas Sekolah</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><router-link tag="a" to="/beranda">Beranda</router-link></li>
                            <li class="breadcrumb-item active">Pakta Integritas Sekolah</li>
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
                                <h3>PAKTA INTEGRITAS SEKOLAH</h3>
                                <span class="text-muted"><i class="fas fa-clock"></i> {{tanggal}}</span>

                                <p>Dengan ini Saya sebagai Kepala Sekolah {{nama_sekolah}} menyatakan bahwa data yang diisi pada kuesioner Penjaminan Mutu SMK tahun pendataan {{tahun_pendataan}} telah diperiksa kebenarannya dan telah sesuai dengan fakta yang ada di lapangan.</p>

                                <p>Saya sepenuhnya siap bertanggung jawab apabila di kemudian hari ditemukan ketidaksesuaian antara data yang diisi di kuesioner Penjaminan Mutu SMK dengan fakta yang ada di lapangan, dan Saya siap menerima sanksi moral, sanksi administrasi, dan sanksi hukum sesuai dengan peraturan dan perundang-undangan yang berlaku.</p>

                                <p>Penanggungjawab</p>

                                <p>Kepala Sekolah {{nama_sekolah}}</p>
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input :disabled='isCheckbox' class="custom-control-input" type="checkbox" id="terms" v-model='terms'>
                                        <label for="terms" class="custom-control-label">Saya setuju dengan pakta integritas sekolah di atas</label>
                                    </div>
                                </div>
                                <b-button squared variant="warning" size="lg" :disabled='isDisabled' v-on:click="cetak_pakta">
                                    <b-spinner small v-show="show_spinner_cetak"></b-spinner>
                                    <span class="sr-only" v-show="show_spinner_cetak">Loading...</span>
                                    <span v-show="show_text_cetak">CETAK PAKTA INTEGRITAS</span>
                                </b-button>
                                <b-button squared variant="danger" size="lg" :disabled='isBatal' v-on:click="batal_pakta">
                                    <b-spinner small v-show="show_spinner_batal"></b-spinner>
                                    <span class="sr-only" v-show="show_spinner_batal">Loading...</span>
                                    <span v-show="show_text_batal">BATALKAN PAKTA INTEGRITAS</span>
                                </b-button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <my-loader/>
    </div>
</template>
<script>
    import axios from 'axios' //IMPORT AXIOS
    export default {
    //KETIKA COMPONENT INI DILOAD
        data() {
            return {
                terms: false,
                tanggal : null,
                nama_sekolah : null,
                tahun_pendataan: null,
                isBatal : true,
                isCheckbox : false,
                show_text_cetak:true,
                show_text_batal:true,
                show_spinner_cetak:false,
                show_spinner_batal: false,
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
            cetak_pakta : function (event) {
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Jika ada kesalahan pengisian instrumen, Anda dapat membatalkan Pakta Integritas ini sebelum di validasi oleh Tim Verifikator!",
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.value) {
                        this.show_text_cetak = false
                        this.show_spinner_cetak = true
                        axios.post(`/api/rapor-mutu/pra-cetak-pakta`, {
                            user_id: user.user_id,
                        }).then((response) => {
                            this.loadPostsData()
                            axios.get(`/api/rapor-mutu/cetak-pakta`, {
                                params : {
                                    user_id: user.user_id,
                                },
                                responseType: 'arraybuffer'
                            }).then((response) => {
                                let blob = new Blob([response.data], { type: 'application/pdf' })
                                let link = document.createElement('a')
                                link.href = window.URL.createObjectURL(blob)
                                link.download = 'Pakta Integritas Penjaminan Mutu SMK.pdf'
                                link.click()
                                this.show_text_cetak = true
                                this.show_spinner_cetak = false
                                this.isBatal = false
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
                        this.show_spinner_batal = true
                        this.show_text_batal = false
                        axios.post(`/api/rapor-mutu/batal-pakta`, {
                            user_id: user.user_id,
                        }).then((response) => {
                            Swal.fire(
                                response.data.title,
                                response.data.text,
                                response.data.icon
                            ).then(()=>{
                                this.loadPostsData()
                            });
                        })
                    }
                })
            },
            loadPostsData() {
                axios.post(`/api/rapor-mutu/pakta`, {
                    user_id: user.user_id,
                })
                .then((response) => {
                    let getData = response.data
                    this.nama_sekolah = getData.user.name
                    this.tahun_pendataan = getData.tahun_pendataan.tahun_pendataan_id
                    this.tanggal = (getData.user.sekolah.sekolah_sasaran) ? (getData.user.sekolah.sekolah_sasaran.pakta_integritas) ? this.tanggalIndo(getData.user.sekolah.sekolah_sasaran.pakta_integritas.created_at) : '-' : '-'
                    this.isBatal = (getData.user.sekolah.sekolah_sasaran) ? (getData.user.sekolah.sekolah_sasaran.pakta_integritas) ? false : true : true
                    if(getData.user.nilai_akhir && this.isBatal){
                        this.isCheckbox = false
                    } else {
                        this.isCheckbox = true
                    }
                    this.terms = false
                    this.show_spinner_batal = false
                    this.show_text_batal = true
                })
            },
            tanggalIndo(time){
                if(!time){
                    return false
                }
                let date = new Date(time)
                let tahun = date.getFullYear()
                let bulan = date.getMonth()
                let tanggal = date.getDate()
                let hari = date.getDay()
                let jam = date.getHours()
                let menit = (date.getMinutes()<10?'0':'') + date.getMinutes()
                let detik = date.getSeconds()
                switch(hari) {
                        case 0: hari = "Minggu"
                    break
                        case 1: hari = "Senin"
                    break
                        case 2: hari = "Selasa"
                    break
                        case 3: hari = "Rabu"
                    break
                        case 4: hari = "Kamis"
                    break
                        case 5: hari = "Jum'at"
                    break
                        case 6: hari = "Sabtu"
                    break
                }
                switch(bulan) {
                    case 0: bulan = "Januari"
                    break
                    case 1: bulan = "Februari"
                        break
                    case 2: bulan = "Maret"
                        break
                    case 3: bulan = "April"
                        break
                    case 4: bulan = "Mei"
                        break
                    case 5: bulan = "Juni"
                        break
                    case 6: bulan = "Juli"
                        break
                    case 7: bulan = "Agustus"
                        break
                    case 8: bulan = "September"
                        break
                    case 9: bulan = "Oktober"
                        break
                    case 10: bulan = "November"
                        break
                    case 11: bulan = "Desember"
                    break
                }
                let result =  hari + ", " + tanggal + " " + bulan + " " + tahun+ " " + jam + ":" + menit + ":" + detik;
                return result
                //var tampilWaktu = "Jam: " + jam + ":" + menit + ":" + detik;
            },
        }
    }
</script>
