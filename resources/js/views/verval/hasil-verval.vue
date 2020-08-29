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
                            <li class="breadcrumb-item">
                                <router-link tag="a" to="/beranda">Beranda</router-link>
                            </li>
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
                            <div class="card-header">
                                <h3 class="card-title">Silahkan pilih nama sekolah yang akan diperiksa hasil vervalnya
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group row">
                                    <label for="komponen_id" class="col-sm-2 col-form-label">Sekolah</label>
                                    <div class="col-sm-10">
                                        <v-select :options="sekolah" label="text" index="value"
                                            @input="getInstrumen" v-model="form.sekolah_id"
                                            placeholder="== Pilih Sekolah == "></v-select>
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
                                            <td class="text-center">
                                                {{(value.nilai_komponen) ? value.nilai_komponen.total_nilai : 0}}</td>
                                            <td class="text-center">
                                                {{(value.nilai_komponen_verifikasi) ? value.nilai_komponen_verifikasi.total_nilai : 0}}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="mt-3" v-show="isShow">
                                    <b-button squared variant="primary" size="lg" v-on:click="simpan_verval">
                                        <b-spinner small v-show="show_spinner_kirim"></b-spinner>
                                        <span class="sr-only" v-show="show_spinner_kirim">Loading...</span>
                                        <span v-show="show_text_kirim">SIMPAN HASIL VERIFIKASI &amp; VALIDASI</span>
                                    </b-button>
                                    <!--div v-show="progress=='waiting' || progress==''">
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input :disabled='isCheckbox' class="custom-control-input" type="checkbox"
                                                    id="terms" v-model='terms'>
                                                <label for="terms" class="custom-control-label">Saya setuju dengan
                                                    pernyataan di atas</label>
                                            </div>
                                        </div>
                                        <b-button squared variant="primary" size="lg" :disabled='isDisabled' v-on:click="kirim_Sverval">
                                            <b-spinner small v-show="show_spinner_kirim"></b-spinner>
                                            <span class="sr-only" v-show="show_spinner_kirim">Loading...</span>
                                            <span v-show="show_text_kirim">KIRIM HASIL VERIFIKASI &amp; VALIDASI</span>
                                        </b-button>
                                        <b-button squared variant="danger" size="lg" :disabled='isBatal' v-on:click="batal_verval">
                                            <b-spinner small v-show="show_spinner_batal"></b-spinner>
                                            <span class="sr-only" v-show="show_spinner_batal">Loading...</span>
                                            <span v-show="show_text_batal">BATALKAN KIRIM HASIL VERIFIKASI &amp; VALIDASI</span>
                                        </b-button>
                                    </div-->
                                </div>
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
                isShow : false,
                form: new Form({
                    verifikator_id:user.user_id,
                    sekolah_id: '',
                }),
                sekolah: [],
                simpan:false,
                komponen: [],
                show_spinner_kirim: false,
                show_text_kirim: true,
                show_spinner_batal: false,
                show_text_batal: true
            }
        },
        created() {
            this.loadPostsData()
        },
        methods: {
            simpan_verval : function (event) {
                this.show_spinner_kirim = true
                this.show_text_kirim = false
                axios.post(`/api/verifikasi/kirim-verval`, {
                    user_id: user.user_id,
                    sekolah_sasaran_id: this.form.sekolah_id.sekolah_sasaran_id
                }).then((response) => {
                    console.log(response)
                    Swal.fire({
                        title: response.data.title,
                        text: response.data.text,
                        icon: response.data.icon
                    }).then(()=>{
                        this.isShow = false
                        this.komponen = []
                        this.form.sekolah_id = ''
                        this.isCheckbox = true
                        this.isBatal = false
                        this.terms = ''
                        this.loadPostsData()
                    });
                })
            },
            getInstrumen(e){
                if(!e){
                    this.komponen = []
                    this.isShow = false
                    this.terms = false
                    return false;
                }
                axios.post(`/api/verifikasi/instrumen`, {
                    verifikator_id: user.user_id,
                    sekolah_id: e.value
                })
                .then((response) => {
                    let getData = response.data
                    this.komponen = getData.data
                    this.isShow = true
                    let raporMutu = getData.sekolah.sekolah_sasaran.rapor_mutu
                    if(raporMutu){
                        this.isCheckbox = true
                        this.isBatal = false
                        this.terms = ''
                        this.progress = raporMutu.status_rapor.status
                        this.keterangan = raporMutu.keterangan
                    } else {
                        this.isCheckbox = false
                        this.isBatal = true
                        this.terms = ''
                        this.progress = ''
                        this.keterangan = ''
                    }
                    
                })
            },
            loadPostsData() {
                axios.post(`/api/verifikasi/sekolah`, {
                    verifikator_id: user.user_id,
                    supervisi:0,
                })
                .then((response) => {
                    let getData = response.data
                    this.sekolah = getData.result
                    this.show_spinner_kirim = false
                    this.show_text_kirim = true
                    this.show_spinner_batal = false
                    this.show_text_batal = true
                })
            }
        }
    }
</script>