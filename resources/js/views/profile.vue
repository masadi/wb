<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Profil Pengguna</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><router-link tag="a" to="/beranda">Beranda</router-link></li>
                            <li class="breadcrumb-item active">Profil Pengguna</li>
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
                                <h3 class="card-title">
                                    <i class="fas fa-user mr-1"></i>
                                    Profil Pengguna
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-danger" v-show="errors">
                                <h5><i class="icon fas fa-ban"></i> Isian Tidak Valid!</h5>
                                <ul>
                                    <li v-for="(error, key) in errors">
                                        {{error}}
                                    </li>
                                </ul>
                                </div>
                                <div class="row">
                                    <div class="col-md-8">
                                        <label for="name" class="col-form-label">Nama Lengkap</label>
                                        <div class="form-group">
                                            <input v-model="form.user_id" type="hidden" name="user_id"
                                                class="form-control">
                                            <input v-model="form.name" type="text" name="name"
                                                class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                                            <has-error :form="form" field="name"></has-error>
                                        </div>
                                        <label for="email" class="col-form-label">Email</label>
                                        <div class="form-group">
                                            <input v-model="form.email" type="email" id="email" name="email" class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
                                            <has-error :form="form" field="email"></has-error>
                                        </div>
                                        <template v-if="hasRole('penjamin_mutu')">
                                            <label for="nuptk" class="col-form-label">NUPTK</label>
                                            <div class="form-group">
                                                <input v-model="form.nuptk" type="text" id="nuptk" name="nuptk" class="form-control" :class="{ 'is-invalid': form.errors.has('nuptk') }">
                                                <has-error :form="form" field="nuptk"></has-error>
                                            </div>
                                            <label for="nip" class="col-form-label">NIP</label>
                                            <div class="form-group">
                                                <input v-model="form.nip" type="text" id="nip" name="nip" class="form-control" :class="{ 'is-invalid': form.errors.has('nip') }">
                                                <has-error :form="form" field="nip"></has-error>
                                            </div>
                                            <label for="asal_institusi" class="col-form-label">Asal Institusi</label>
                                            <div class="form-group">
                                                <input v-model="form.nuptk" type="text" id="asal_institusi" name="asal_institusi" class="form-control" :class="{ 'is-invalid': form.errors.has('asal_institusi') }">
                                                <has-error :form="form" field="asal_institusi"></has-error>
                                            </div>
                                            <label for="alamat_institusi" class="col-form-label">Alamat Institusi</label>
                                            <div class="form-group">
                                                <input v-model="form.alamat_institusi" type="text" id="alamat_institusi" name="alamat_institusi" class="form-control" :class="{ 'is-invalid': form.errors.has('alamat_institusi') }">
                                                <has-error :form="form" field="alamat_institusi"></has-error>
                                            </div>
                                            <label for="nomor_hp" class="col-form-label">Nomor HP</label>
                                            <div class="form-group">
                                                <input v-model="form.nomor_hp" type="text" id="nomor_hp" name="nomor_hp" class="form-control" :class="{ 'is-invalid': form.errors.has('nomor_hp') }">
                                                <has-error :form="form" field="nomor_hp"></has-error>
                                            </div>
                                        </template>
                                        <label for="current-password" class="col-form-label">Sandi Saat Ini (Biarkan kosong jika tidak ingin
                                            merubah)</label>
                                        <div class="form-group">
                                            <input v-model="form.current_password" type="password" id="current-password" name="current_password" class="form-control" :class="{ 'is-invalid': form.errors.has('current_password') }" placeholder="Sandi saat ini" autocomplete="new-password">
                                            <has-error :form="form" field="current_password"></has-error>
                                        </div>
                                        <label for="password" class="col-form-label">Sandi Baru</label>
                                        <div class="form-group">
                                            <input v-model="form.password" type="password" id="password" name="password" class="form-control" :class="{ 'is-invalid': form.errors.has('password') }" placeholder="Sandi baru" autocomplete="password">
                                            <has-error :form="form" field="password"></has-error>
                                        </div>
                                        <label for="password_confirmation" class="col-form-label">Konfirmasi Sandi</label>
                                        <div class="form-group">
                                            <input v-model="form.password_confirmation" type="password" id="password_confirmation" name="password_confirmation" class="form-control" :class="{ 'is-invalid': form.errors.has('password_confirmation') }" placeholder="Ketik ulang kata sandi baru" autocomplete="password_confirmation">
                                            <has-error :form="form" field="password_confirmation"></has-error>
                                        </div>
                                    </div>
                                    <div class="col-md-4 text-center">
                                        <label for="current-password" class="col-form-label">Foto Profil</label>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <img :src="photo">
                                                    <div class="custom-file">
                                                        <label class="custom-file-label" for="image">Cari Berkas</label>
                                                        <!--input type="file" class="custom-file-input" id="image" name="image"-->
                                                        <!--input type="file" name="image" id="image" @change="fileUpload($event.target)"
                                                            class="form-control" :class="{ 'is-invalid': form.errors.has('image') }">
                                                        <has-error :form="form" field="image"></has-error-->
                                                        <b-form-file v-model="upload_photo" :state="Boolean(upload_photo)" accept=".jpg, .png, .jpeg" placeholder="Cari berkas foto..." drop-placeholder="Letakkan berkas foto disini..."></b-form-file>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <b-button squared variant="success" size="lg" v-on:click="updateData">
                                    <b-spinner small v-show="show_spinner"></b-spinner>
                                    <span class="sr-only" v-show="show_spinner">Loading...</span>
                                    <span v-show="show_text">Perbaharui Data</span>
                                </b-button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>
<script>
import objectToFormData from "./components/objectToFormData"; 
import axios from 'axios' //IMPORT AXIOS
export default {
    //KETIKA COMPONENT INI DILOAD
    created() {
        //MAKA AKAN MENJALANKAN FUNGSI BERIKUT
        this.loadPostsData()
    },
    data() {
        return {
            form: new Form({
                user_id: '',
                name : '',
                email: '',
                current_password: '',
                password: '',
                password_confirmation: '',
                nuptk: '',
                nip: '',
                asal_institusi:'',
                alamat_institusi: '',
                nomor_hp: '',
                photo: null,
            }),
            errors:null,
            photo : '',
            upload_photo: [],
            show_spinner: false,
            show_text: true,
        }
    },
    methods: {
        loadPostsData() {
            axios.post(`/api/profile`, {
                //KIRIMKAN PARAMETER BERUPA PAGE YANG SEDANG DILOAD, PENCARIAN, LOAD PERPAGE DAN SORTING.
                user_id: user.user_id,
            })
            .then((response) => {
                //JIKA RESPONSENYA DITERIMA
                let getData = response.data.data
                this.form.user_id = getData.user_id
                this.form.name = getData.name
                this.form.email = getData.email
                this.form.current_password = ''
                this.form.password = ''
                this.form.password_confirmation = ''
                this.form.nuptk = getData.nuptk
                this.form.nip = getData.nip
                this.form.asal_institusi = getData.asal_institusi
                this.form.alamat_institusi = getData.alamat_institusi
                this.form.nomor_hp = getData.nomor_hp
                this.upload_photo = []
                this.photo = (getData.photo) ? '/images/245/'+getData.photo : '/vendor/img/avatar3.png'
                this.show_spinner = false
                this.show_text = true
            })
        },
        updateData(){
            this.show_spinner = true
            this.show_text = false
            this.errors = null
            let formData = new FormData();
            formData.append('image', this.upload_photo);
            formData.append('user_id', this.form.user_id)
            formData.append('name', this.form.name)
            formData.append('email', this.form.email)
            formData.append('current_password', this.form.current_password)
            formData.append('password', this.form.password)
            formData.append('password_confirmation', this.form.password_confirmation)
            formData.append('nuptk', this.form.nuptk)
            formData.append('nip', this.form.nip)
            formData.append('asal_institusi', this.form.asal_institusi)
            formData.append('alamat_institusi', this.form.alamat_institusi)
            formData.append('nomor_hp', this.form.nomor_hp)
            axios.post('/api/update-profile', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                },
            }).then((response) => {
                console.log(response);
                Toast.fire({
                    icon: 'success',
                    title: response.data.message
                });
                this.loadPostsData();
            }).catch(error => {
                console.log(error.response.data)
                var errors = [];
                $.each(error.response.data.errors, function(key, value){
                    errors.push(value[0]);
                })
                console.log(errors);
                this.errors = errors
                this.show_spinner = false
                this.show_text = true
            });
        }
    }
}
</script>
