<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Kirim Laporan Hasil Supervisi</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><router-link tag="a" to="/beranda">Beranda</router-link></li>
                            <li class="breadcrumb-item active">Kirim Laporan Hasil Supervisi</li>
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
                                <h3 class="card-title">Silahkan pilih nama sekolah yang akan dilaporkan hasil supervisinya
                                </h3>
                            </div>
                            <form class="needs-validation" novalidate>
                                <div class="card-body">
                                    <input type="hidden" v-model="form.rapor_mutu_id">
                                    <input type="hidden" v-model="form.verifikator_id">
                                    <input type="hidden" v-model="form.sekolah_sasaran_id">
                                    <div class="form-group row">
                                        <label for="komponen_id" class="col-sm-2 col-form-label">Sekolah</label>
                                        <div class="col-sm-10">
                                            <v-select :options="sekolah" label="text" index="value"
                                                @input="getLaporan" v-model="sekolah_id"
                                                placeholder="== Pilih Sekolah == "></v-select>
                                        </div>
                                    </div>
                                    <div class="form-group row" v-show="simpan">
                                        <label for="keterangan" class="col-sm-2 col-form-label">Isi Laporan</label>
                                        <div class="col-sm-10">
                                            <ckeditor editor="classic" tag-name="textarea" v-model="form.keterangan" :editor="editor" :editorData="editorData" :config="editorConfig" :disabled="editorDisabled" @ready="onEditorReady" @focus="onEditorFocus" @blur="onEditorBlur" @input="onEditorInput" @destroy="onEditorDestroy"></ckeditor>
                                        </div>
                                    </div>
                                    <div class="form-group  row" v-show="simpan">
                                        <label class="col-sm-2 col-form-label">Unggah Berita Acara</label>
                                        <div class="col-sm-10">
                                            <input type="file" ref="fileupload" name="file" @change="fileUpload($event.target)"
                                            class="form-control" :disabled="editorDisabled" :class="{ 'is-invalid': form.errors.has('file') }">
                                            <div class="invalid-feedback" v-bind:style="{ display: displayError }">
                                                {{errorText}}
                                            </div>
                                            <div class="valid-feedback" v-bind:style="{ display: displaySuccess }">
                                                Berkas berita acara berhasil di unggah
                                            </div>
                                            <div class="progress my-2" v-show="progressBar > 0">
                                                <div class="progress-bar" role="progressbar" 
                                                    :style="{width: progressBar + '%'}" 
                                                    :aria-valuenow="progressBar" 
                                                    aria-valuemin="0" 
                                                    aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div v-show="simpan">
                                        <h3>PERNYATAAN PENGIRIMAN LAPORAN HASIL VERIFIKASI DAN VALIDASI</h3>
                                        <p>Dengan ini Saya sebagai Tim Verifikator menyatakan bahwa data yang diisi oleh
                                            sekolah <strong>{{nama_sekolah}}</strong> pada kuesioner Penjaminan Mutu SMK
                                            <strong>tahun pendataan {{tahun_pendataan}}</strong> telah diperiksa
                                            kebenarannya dan telah sesuai dengan fakta yang ada di lapangan.</p>

                                        <p>Saya sepenuhnya siap bertanggung jawab apabila di kemudian hari ditemukan
                                            ketidaksesuaian antara data yang diisi di kuesioner Penjaminan Mutu SMK dengan
                                            fakta yang ada di lapangan, dan Saya siap menerima sanksi moral, sanksi
                                            administrasi, dan sanksi hukum sesuai dengan peraturan dan perundang-undangan
                                            yang berlaku.</p>

                                        <p>Tim Verifikator</p>

                                        <p class="text-bold">{{detilUser.name}}</p>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input :disabled='isCheckbox' class="custom-control-input" type="checkbox"
                                                    id="terms" v-model='terms'>
                                                <label for="terms" class="custom-control-label">Saya setuju dengan
                                                    pernyataan di atas</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-3" v-show="simpan">
                                        <div v-show="progress_rapor_mutu=='waiting'" class="alert alert-warning">
                                            <h5><i class="icon fas fa-exclamation-triangle"></i> LAPORAN MENUNGGU DIPROSES!</h5>
                                            Laporan hasil verifikasi dan validasi sedang dalam antrian.
                                        </div>
                                        <div v-show="progress_rapor_mutu=='proses'" class="alert alert-info">
                                            <h5><i class="icon fas fa-info"></i> LAPORAN SEDANG DIPROSES!</h5>
                                            Laporan hasil verifikasi dan validasi saat ini sedang dalam proses pemeriksaan
                                        </div>
                                        <div v-show="progress_rapor_mutu=='terima'" class="alert alert-success">
                                            <h5><i class="icon fas fa-check"></i> LAPORAN DITERIMA</h5>
                                            Laporan hasil verifikasi dan validasi {{nama_sekolah}} diterima
                                        </div>
                                        <div v-show="progress_rapor_mutu=='tolak'" class="alert alert-danger">
                                            <h5><i class="icon fas fa-ban"></i> LAPORAN DITOLAK!</h5>
                                            Laporan hasil verifikasi dan validasi {{nama_sekolah}} ditolak
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div class="card-footer" v-show="simpan">
                                <!--button type="button" class="btn btn-lg btn-primary btn-flat" v-on:click="kirimLaporan">KIRIM LAPORAN SUPERVISI</button-->
                                <b-button squared variant="primary" size="lg" :disabled='isDisabled' v-on:click="kirimLaporan">
                                    <b-spinner small v-show="show_spinner_kirim"></b-spinner>
                                    <span class="sr-only" v-show="show_spinner_kirim">Loading...</span>
                                    <span v-show="show_text_kirim">KIRIM LAPORAN SUPERVISI HASIL VERIFIKASI &amp; VALIDASI</span>
                                </b-button>
                                <b-button squared variant="danger" size="lg" :disabled='isBatal' v-on:click="batalLaporan">
                                    <b-spinner small v-show="show_spinner_batal"></b-spinner>
                                    <span class="sr-only" v-show="show_spinner_batal">Loading...</span>
                                    <span v-show="show_text_batal">BATALKAN KIRIM LAPORAN HASIL VERIFIKASI &amp; VALIDASI</span>
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
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
    export default {
    //KETIKA COMPONENT INI DILOAD
        data() {
            return {
                isCheckbox : false,
                terms: false,
                isBatal : true,
                isShow : false,
                nama_sekolah : null,
                tahun_pendataan: null,
                displayError: 'none',
                displaySuccess: 'none',
                errorText: '',
                progressBar: 0,
                progress_rapor_mutu: '',
                keterangan: '',
                editor: ClassicEditor,
                editorData: '',
                editorConfig: { 
                    height: 1900,
                },
                editorDisabled: false,
                form: new Form({
                    rapor_mutu_id: '',
                    sekolah_sasaran_id: '',
                    verifikator_id : '',
                    keterangan: '',
                }),
                sekolah_id: '',
                sekolah: [],
                simpan:false,
                show_spinner_kirim: false,
                show_text_kirim: true,
                show_spinner_batal: false,
                show_text_batal: true
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
            fileUpload(event) {
                this.file = event.files[0]
                this.isLoading = true
                let formData = new FormData();
                formData.append('rapor_mutu_id', this.form.rapor_mutu_id);
                formData.append('verifikator_id', this.form.verifikator_id);
                formData.append('sekolah_sasaran_id', this.form.sekolah_sasaran_id);
                formData.append('file', this.file);
                axios.post('/api/verifikasi/upload', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    },
                    //FUNGSI INI YANG MEMILIKI PERAN UNTUK MENGUBAH SEBERAPA JAUH PROGRESS UPLOAD FILE BERJALAN
                    onUploadProgress: function( progressEvent ) {
                        //DATA TERSEBUT AKAN DI ASSIGN KE VARIABLE progressBar
                        this.progressBar = parseInt(Math.round((progressEvent.loaded * 100) / progressEvent.total))
                    }.bind(this)
                }).then((response) => {
                    this.displaySuccess = 'block'
                    setTimeout(() => {
                        this.progressBar = 0
                    }, 3000)
                }).catch(error => {
                    this.displayError = 'block'
                    var errors = [];
                    $.each(error.response.data.errors, function(i, item){
                        errors.push(item[0]);
                    })
                    this.errorText = errors.join('<br>')
                    setTimeout(() => {
                        this.progressBar = 0
                    }, 3000)
                })
            },
            //CKEDITOR START
            toggleEditorDisabled() {
                this.editorDisabled = !this.editorDisabled;
            },
            destroyApp() {
                app.$destroy();
            },
            onEditorReady( editor ) {
                //console.log( 'Editor is ready.', { editor } );
            },
            onEditorFocus( event, editor ) {
                //console.log( 'Editor focused.', { event, editor } );
            },
            onEditorBlur( event, editor ) {
                //console.log( 'Editor blurred.', { event, editor } );
            },
            onEditorInput( data, event, editor ) {
                //console.log( 'Editor data input.', { event, editor, data } );
            },
            onEditorDestroy( editor ) {
                //console.log( 'Editor destroyed.', { editor } );
            },
            //CKEDITOR END
            getLaporan(e){
                if(!e){
                    //this.sekolah = []
                    this.simpan = false
                    return false
                }
                if(!e.value){
                    //this.sekolah = []
                    this.simpan = false
                    return false;
                }
                console.log(e)
                axios.post(`/api/verifikasi/laporan`, {
                    verifikator_id: user.user_id,
                    sekolah_id: e.value,
                    sekolah_sasaran_id: e.sekolah_sasaran_id
                })
                .then((response) => {
                    let getData = response.data
                    //console.log(getData.data)
                    //return false
                    this.form.rapor_mutu_id = getData.data.sekolah_sasaran.waiting.rapor_mutu_id
                    this.form.sekolah_sasaran_id = getData.data.sekolah_sasaran.sekolah_sasaran_id
                    this.form.verifikator_id = getData.data.sekolah_sasaran.verifikator_id
                    if(getData.data.sekolah_sasaran.waiting.keterangan){
                        this.form.keterangan = getData.data.sekolah_sasaran.waiting.keterangan
                    }
                    var status_rapor = 'waiting'
                    if(getData.data.sekolah_sasaran.proses){
                        this.toggleEditorDisabled()
                        this.isCheckbox = true
                        this.isBatal = true
                        status_rapor = 'proses'
                    } else {
                        this.isCheckbox = true
                        this.isBatal = false
                    }
                    if(getData.data.sekolah_sasaran.terima){
                        status_rapor = 'terima'
                    }
                    if(getData.data.sekolah_sasaran.tolak){
                        status_rapor = 'tolak'
                    }
                    this.progress_rapor_mutu = status_rapor
                    this.terms = false
                    this.simpan = true
                    this.isShow = true
                    this.nama_sekolah = getData.data.nama
                    this.tahun_pendataan = getData.data.sekolah_sasaran.tahun_pendataan_id
                    const input = this.$refs.fileupload;
                    input.type = 'text'
                    input.type = 'file'
                    this.displayError = 'none'
                    this.displaySuccess = 'none'
                })
            },
            kirimLaporan(){
                this.form.post('/api/verifikasi/kirim').then((response)=>{
                    Toast.fire({
                        icon: response.icon,
                        title: response.message
                    });
                    this.simpan = false
                    this.loadPostsData();
                }).catch((error)=>{
                    console.log(error);
                    var errors = [];
                    $.each(error, function(i, item){
                        errors.push(item[0]);
                    })
                    //this.errorText = errors.join('<br>')
                    /*Toast.fire({
                        icon: 'error',
                        title: errors.join('<br>')
                    });*/
                    Swal.fire({
                        title: 'Gagal',
                        html: errors.join('<br>'),
                        icon: 'error'
                    })
                })
            },
            batalLaporan : function (event) {
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Proses pembatalan laporan hasil verifikasi dan validasi!",
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
                        axios.post(`/api/verifikasi/batal-verval`, {
                            user_id: user.user_id,
                            sekolah_sasaran_id: this.form.sekolah_sasaran_id
                        }).then((response) => {
                            Swal.fire(
                                response.data.title,
                                response.data.text,
                                response.data.icon
                            ).then(()=>{
                                /*this.isShow = false
                                this.komponen = []
                                this.form.sekolah_id = ''
                                this.loadPostsData()*/
                                this.isBatal = true
                                this.isCheckbox = false
                                this.simpan = false
                                this.loadPostsData();
                            });
                        })
                    }
                })
            },
            loadPostsData() {
                axios.post(`/api/verifikasi/sekolah`, {
                    verifikator_id: user.user_id,
                    supervisi: 1,
                })
                .then((response) => {
                    let getData = response.data
                    this.sekolah = getData.result
                    this.form.rapor_mutu_id = ''
                    this.sekolah_id = ''
                    this.form.keterangan = ''
                    this.terms = false
                    this.show_spinner_kirim = false
                    this.show_text_kirim = true
                    this.show_spinner_batal = false
                    this.show_text_batal = true
                })
            }
        }
    }
</script>
