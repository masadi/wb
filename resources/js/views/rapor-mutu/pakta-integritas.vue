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
                        <li class="breadcrumb-item">
                            <router-link tag="a" to="/beranda">Beranda</router-link>
                        </li>
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
                    <div class="card" v-show="!is_coe">
                        <div class="card-body">
                            <h3 class="text-center" v-html="no_coe"></h3>
                        </div>
                    </div>
                    <div class="card" v-show="is_coe">
                        <div class="card-header">
                            <div class="card-tools">
                                <b-button squared variant="primary" size="lg" v-show='allowCetak' v-on:click="cetak_pakta_no_alert">
                                    <b-spinner small v-show="show_spinner_cetak_no_alert"></b-spinner>
                                    <span class="sr-only" v-show="show_spinner_cetak_no_alert">Loading...</span>
                                    <span v-show="show_text_cetak_no_alert">CETAK PAKTA INTEGRITAS</span>
                                </b-button>
                            </div>
                            <h3 class="card-title">PAKTA INTEGRITAS SEKOLAH</h3>
                            <br>
                            <div class="text-muted"><i class="fas fa-clock"></i> {{tanggal}}</div>
                        </div>
                        <div class="card-body">
                            <!--
                                <p>Dengan ini Saya sebagai Kepala Sekolah {{nama_sekolah}} menyatakan bahwa data yang diisi pada kuesioner Penjaminan Mutu SMK tahun pendataan {{tahun_pendataan}} telah diperiksa kebenarannya dan telah sesuai dengan fakta yang ada di lapangan.</p>

                                <p>Saya sepenuhnya siap bertanggung jawab apabila di kemudian hari ditemukan ketidaksesuaian antara data yang diisi di kuesioner Penjaminan Mutu SMK dengan fakta yang ada di lapangan, dan Saya siap menerima sanksi moral, sanksi administrasi, dan sanksi hukum sesuai dengan peraturan dan perundang-undangan yang berlaku.</p>

                                <p>Penanggungjawab</p>

                                <p>Kepala Sekolah {{nama_sekolah}}</p>
                                -->
                            <p>Yang bertanda tangan di bawah ini :</p>
                            <table width="100%">
                                <tr>
                                    <td width="10%">Nama</td>
                                    <td width="1%">:</td>
                                    <td width="89%">{{nama_kepsek}}</td>
                                </tr>
                                <tr>
                                    <td>NIP</td>
                                    <td>:</td>
                                    <td>{{nip_kepsek}}</td>
                                </tr>
                                <tr>
                                    <td>Jabatan</td>
                                    <td>:</td>
                                    <td>Kepala Sekolah</td>
                                </tr>
                                <tr>
                                    <td>Unit Kerja</td>
                                    <td>:</td>
                                    <td>{{nama_sekolah}}</td>
                                </tr>
                            </table>
                            <p>Menyatakan bahwa seluruh data yang diisikan dalam instrumen Aplikasi Penjaminan Mutu SMK (APM SMK) sudah sesuai dengan data yang sebenarnya.</p>
                            <p>Jika dikemudian hari ditemukan ketidaksesuaian antara data yang dikirimkan dengan data yang ada, saya siap menerima sanksi baik secara moral atau administrasi.</p>

                            <p>{{kabupaten_kota}}, {{tanggal_ttd}}<br>
                                Kepala Sekolah</p>
                            <p></p>
                            <p></p>
                            <p>{{nama_kepsek}}<br>
                                NIP. {{nip_kepsek}}</p>

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
                            <b-button squared variant="success" size="lg" :disabled='isKirim' v-on:click="kirim_pakta(pakta_integritas_id)">
                                <b-spinner small v-show="show_spinner_kirim"></b-spinner>
                                <span class="sr-only" v-show="show_spinner_kirim">Loading...</span>
                                <span v-show="show_text_kirim">KIRIM RAPOR MUTU SEKOLAH</span>
                            </b-button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <my-loader />
</div>
</template>

<script>
import axios from 'axios' //IMPORT AXIOS
export default {
    //KETIKA COMPONENT INI DILOAD
    data() {
        return {
            no_coe: 'Loading....',
            is_coe: null,
            allowCetak: false,
            nama_kepsek: null,
            nip_kepsek: null,
            kabupaten_kota: null,
            tanggal_ttd: null,
            terms: false,
            tanggal: null,
            nama_sekolah: null,
            tahun_pendataan: null,
            isBatal: true,
            isKirim: true,
            isCheckbox: false,
            isCetak: false,
            show_text_cetak: true,
            show_text_cetak_no_alert: true,
            show_text_batal: true,
            show_text_kirim: true,
            show_spinner_cetak: false,
            show_spinner_cetak_no_alert: false,
            show_spinner_batal: false,
            show_spinner_kirim: false,
            pakta_integritas_id: null,
        }
    },
    computed: {
        isDisabled: function () {
            return !this.terms;
        }
    },
    created() {
        this.loadPostsData()
    },
    methods: {
        kirim_pakta: function (pakta_integritas_id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Setelah proses KIRIM RAPOR MUTU SEKOLAH, Anda tidak akan diperkenankan lagi untuk membatalkan PAKTA INTEGRITAS!",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.value) {
                    this.show_text_kirim = false
                    this.show_spinner_kirim = true
                    axios.post(`/api/rapor-mutu/kirim`, {
                        user_id: user.user_id,
                        pakta_integritas_id: this.pakta_integritas_id
                    }).then((response) => {
                        Swal.fire(
                            response.data.title,
                            response.data.text,
                            response.data.icon
                        ).then(() => {
                            this.loadPostsData()
                        });
                    })
                }
            })
        },
        cetak_pakta: function (event) {
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
                            params: {
                                user_id: user.user_id,
                            },
                            responseType: 'arraybuffer'
                        }).then((response) => {
                            let blob = new Blob([response.data], {
                                type: 'application/pdf'
                            })
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
        batal_pakta: function (event) {
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
                        ).then(() => {
                            this.loadPostsData()
                        });
                    })
                }
            })
        },
        cetak_pakta_no_alert: function (event) {
            this.show_text_cetak_no_alert = false
            this.show_spinner_cetak_no_alert = true
            axios.post(`/api/rapor-mutu/pra-cetak-pakta`, {
                user_id: user.user_id,
            }).then((response) => {
                this.loadPostsData()
                axios.get(`/api/rapor-mutu/cetak-pakta`, {
                    params: {
                        user_id: user.user_id,
                    },
                    responseType: 'arraybuffer'
                }).then((response) => {
                    let blob = new Blob([response.data], {
                        type: 'application/pdf'
                    })
                    let link = document.createElement('a')
                    link.href = window.URL.createObjectURL(blob)
                    link.download = 'Pakta Integritas Penjaminan Mutu SMK.pdf'
                    link.click()
                    this.show_text_cetak_no_alert = true
                    this.show_spinner_cetak_no_alert = false
                })
            })
        },
        loadPostsData() {
            axios.post(`/api/rapor-mutu/pakta`, {
                    user_id: user.user_id,
                })
                .then((response) => {
                    var currentDate = new Date();
                    var formatted_date = new Date().toJSON().slice(0, 10).replace(/-/g, '-');
                    let getData = response.data
                    this.nama_sekolah = getData.user.name
                    this.nama_kepsek = getData.user.sekolah.nama_kepsek
                    this.nip_kepsek = (getData.user.sekolah.nip_kepsek) ? getData.user.sekolah.nip_kepsek : '-'
                    this.kabupaten_kota = getData.user.sekolah.kabupaten
                    this.tahun_pendataan = getData.tahun_pendataan.tahun_pendataan_id
                    this.tanggal = (getData.user.sekolah.sekolah_sasaran) ? (getData.user.sekolah.sekolah_sasaran.pakta_integritas) ? this.tanggalIndo(getData.user.sekolah.sekolah_sasaran.pakta_integritas.created_at) : '-' : '-'
                    this.tanggal_ttd = (getData.user.sekolah.sekolah_sasaran) ? (getData.user.sekolah.sekolah_sasaran.pakta_integritas) ? this.tanggalIndo(getData.user.sekolah.sekolah_sasaran.pakta_integritas.created_at, false) : this.tanggalIndo(formatted_date, false) : this.tanggalIndo(formatted_date, false)
                    this.isBatal = (getData.user.sekolah.sekolah_sasaran) ? (getData.user.sekolah.sekolah_sasaran.pakta_integritas) ? false : true : true
                    this.no_coe = 'Sekolah Anda belum ditetapkan sebagai SMK Center of Excelent'
                    this.is_coe = (getData.user.sekolah) ? getData.user.sekolah.smk_coe : null
                    if (getData.user.nilai_akhir && this.isBatal) {
                        this.isCheckbox = false
                    } else {
                        this.isCheckbox = true
                    }
                    var npsn_pengecualian = [
                        '50205616',
                        '69947173',
                        '20237410',
                        '69900388',
                        '20217795',
                        '20607873',
                        '20321842'
                    ]
                    var check_npsn = getData.user.sekolah.npsn
                    if (getData.user.sekolah.sekolah_sasaran) {
                        if (getData.user.sekolah.sekolah_sasaran.pakta_integritas) {
                            this.pakta_integritas_id = getData.user.sekolah.sekolah_sasaran.pakta_integritas.pakta_integritas_id
                            if (getData.user.sekolah.sekolah_sasaran.pakta_integritas.terkirim == 0) {
                                this.isKirim = false
                                if (npsn_pengecualian.includes(check_npsn) === true) {
                                    this.no_coe = 'Pengiriman Rapor Mutu Sekolah tidak dapat dilakukan karena telah melewati batas waktu yang ditentukan'
                                    this.is_coe = null
                                }
                            } else {
                                this.isKirim = true
                                this.isBatal = true
                                this.isCheckbox = true
                                this.allowCetak = true
                            }
                        } else {
                            if (npsn_pengecualian.includes(check_npsn) === true) {
                                this.no_coe = 'Pengiriman Rapor Mutu Sekolah tidak dapat dilakukan karena telah melewati batas waktu yang ditentukan'
                                this.is_coe = null
                            }
                            this.isKirim = true
                        }
                    }
                    this.terms = false
                    this.show_spinner_batal = false
                    this.show_text_batal = true
                    this.show_text_kirim = true
                    this.show_spinner_kirim = false
                })
        },
        tanggalIndo(time, full = true) {
            if (!time) {
                return false
            }
            let date = new Date(time)
            let tahun = date.getFullYear()
            let bulan = date.getMonth()
            let tanggal = date.getDate()
            let hari = date.getDay()
            let jam = date.getHours()
            let menit = (date.getMinutes() < 10 ? '0' : '') + date.getMinutes()
            let detik = date.getSeconds()
            switch (hari) {
                case 0:
                    hari = "Minggu"
                    break
                case 1:
                    hari = "Senin"
                    break
                case 2:
                    hari = "Selasa"
                    break
                case 3:
                    hari = "Rabu"
                    break
                case 4:
                    hari = "Kamis"
                    break
                case 5:
                    hari = "Jum'at"
                    break
                case 6:
                    hari = "Sabtu"
                    break
            }
            switch (bulan) {
                case 0:
                    bulan = "Januari"
                    break
                case 1:
                    bulan = "Februari"
                    break
                case 2:
                    bulan = "Maret"
                    break
                case 3:
                    bulan = "April"
                    break
                case 4:
                    bulan = "Mei"
                    break
                case 5:
                    bulan = "Juni"
                    break
                case 6:
                    bulan = "Juli"
                    break
                case 7:
                    bulan = "Agustus"
                    break
                case 8:
                    bulan = "September"
                    break
                case 9:
                    bulan = "Oktober"
                    break
                case 10:
                    bulan = "November"
                    break
                case 11:
                    bulan = "Desember"
                    break
            }
            let result = hari + ", " + tanggal + " " + bulan + " " + tahun + " " + jam + ":" + menit + ":" + detik;
            if (!full) {
                result = tanggal + " " + bulan + " " + tahun;
            }
            return result
            //var tampilWaktu = "Jam: " + jam + ":" + menit + ":" + detik;
        },
    }
}
</script>
