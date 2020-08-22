<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Laporan Hasil Supervisi</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><router-link tag="a" to="/beranda">Beranda</router-link></li>
                            <li class="breadcrumb-item active">Laporan Hasil Supervisi</li>
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
                            <div class="card-body">
                                <input type="hidden" v-model="form.rapor_mutu_id">
                                <div class="form-group row">
                                    <label for="komponen_id" class="col-sm-2 col-form-label">Sekolah</label>
                                    <div class="col-sm-10">
                                        <v-select :options="sekolah" label="text" index="value"
                                            @input="getLaporan" v-model="sekolah_id"
                                            placeholder="== Pilih Sekolah == "></v-select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="keterangan" class="col-sm-2 col-form-label">Isi Laporan</label>
                                    <div class="col-sm-10">
                                        <ckeditor editor="classic" tag-name="textarea" v-model="form.keterangan" :editor="editor" :editorData="editorData" :config="editorConfig" :disabled="editorDisabled" @ready="onEditorReady" @focus="onEditorFocus" @blur="onEditorBlur" @input="onEditorInput" @destroy="onEditorDestroy"></ckeditor>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer" v-show="simpan">
                                <button type="button" class="btn btn-lg btn-primary btn-flat" v-on:click="kirimLaporan">KIRIM LAPORAN SUPERVISI</button>
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
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
    export default {
    //KETIKA COMPONENT INI DILOAD
        data() {
            return {
                editor: ClassicEditor,
                editorData: '',
                editorConfig: { 
                    //
                },
                editorDisabled: false,
                form: new Form({
                    rapor_mutu_id: '',
                    keterangan: '',
                }),
                sekolah_id: '',
                sekolah: [],
                simpan:false,
            }
        },
        created() {
            this.loadPostsData()
        },
        methods: {
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
                    this.sekolah = []
                    this.simpan = false
                    return false
                }
                if(!e.value){
                    this.sekolah = []
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
                    console.log(getData.data.keterangan)
                    this.form.rapor_mutu_id = getData.data.rapor_mutu_id
                    if(getData.data.keterangan){
                        this.form.keterangan = getData.data.keterangan
                    }
                    this.simpan = true
                })
            },
            kirimLaporan(){
                this.form.post('/api/verifikasi/kirim').then((response)=>{
                    Toast.fire({
                        icon: 'success',
                        title: response.message
                    });
                    this.loadPostsData();
                }).catch((e)=>{
                    Toast.fire({
                        icon: 'error',
                        title: 'Some error occured! Please try again'
                    });
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
                })
            }
        }
    }
</script>
