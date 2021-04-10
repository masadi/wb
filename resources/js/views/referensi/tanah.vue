<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Data Tanah</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><router-link tag="a" to="/beranda">Beranda</router-link></li>
                            <li class="breadcrumb-item active">Data Tanah</li>
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
                                <h3 class="card-title">List Tanah</h3>
                                <div class="card-tools" v-show="hasRole('admin')">
                                    <button class="btn btn-success btn-sm btn-block btn-flat" v-on:click="newModal">Tambah Data</button>
                                </div>
                            </div>
                            <div class="card-body">
                                <app-datatable :items="items" :fields="fields" :meta="meta" :title="'Hapus Berita'" @per_page="handlePerPage" @pagination="handlePagination" @search="handleSearch" @sort="handleSort"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <div class="modal fade" id="modalAdd" tabindex="-1" role="dialog" aria-labelledby="modalAdd" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Data Tanah</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="insertData()" method="post">
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Sekolah</label>
                                <v-select label="nama" :options="data_sekolah" v-model="form.sekolah_id" />
                                <has-error :form="form" field="sekolah_id"></has-error>
                            </div>
                            <div class="form-group">
                                <label>Nama</label>
                                <input v-model="form.nama" type="text" name="nama" class="form-control" :class="{ 'is-invalid': form.errors.has('nama') }">
                                <has-error :form="form" field="nama"></has-error>
                            </div>
                            <div class="form-group">
                                <label>Nomor Sertifikat Tanah</label>
                                <input v-model="form.no_sertifikat_tanah" type="text" name="no_sertifikat_tanah" class="form-control" :class="{ 'is-invalid': form.errors.has('no_sertifikat_tanah') }">
                                <has-error :form="form" field="no_sertifikat_tanah"></has-error>
                            </div>
                            <div class="form-group">
                                <label>Panjang (m)</label>
                                <input v-model="form.panjang" type="text" name="panjang" class="form-control" :class="{ 'is-invalid': form.errors.has('panjang') }">
                                <has-error :form="form" field="panjang"></has-error>
                            </div>
                            <div class="form-group">
                                <label>Lebar (m)</label>
                                <input v-model="form.lebar" type="text" name="lebar" class="form-control" :class="{ 'is-invalid': form.errors.has('lebar') }">
                                <has-error :form="form" field="lebar"></has-error>
                            </div>
                            <div class="form-group">
                                <label>Luas (m<sup>2</sup>)</label>
                                <input v-model="form.luas" type="text" name="luas" class="form-control" :class="{ 'is-invalid': form.errors.has('luas') }">
                                <has-error :form="form" field="luas"></has-error>
                            </div>
                            <div class="form-group">
                                <label>Kepemilikan</label>
                                <input v-model="form.kepemilikan" type="text" name="kepemilikan" class="form-control" :class="{ 'is-invalid': form.errors.has('kepemilikan') }">
                                <has-error :form="form" field="kepemilikan"></has-error>
                            </div>
                            <div class="form-group">
                                <label>Luas Lahan Tersedia (m<sup>2</sup>)</label>
                                <input v-model="form.luas_lahan_tersedia" type="text" name="luas_lahan_tersedia" class="form-control" :class="{ 'is-invalid': form.errors.has('luas_lahan_tersedia') }">
                                <has-error :form="form" field="luas_lahan_tersedia"></has-error>
                            </div>
                            <div class="form-group">
                                <label>keterangan</label>
                                <input v-model="form.keterangan" type="text" name="keterangan" class="form-control" :class="{ 'is-invalid': form.errors.has('keterangan') }">
                                <has-error :form="form" field="keterangan"></has-error>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <my-loader/>
    </div>
</template>
<script>
    // VueJS components will run here.
    import Datatable from './../components/Tanah.vue' //IMPORT COMPONENT DATATABLENYA
    import axios from 'axios' //IMPORT AXIOS
    import objectToFormData from "./../components/objectToFormData"; 
    //window.objectToFormData = objectToFormData;
    //const objectToFormData = window.objectToFormData
export default {
    //KETIKA COMPONENT INI DILOAD
    created() {
        //MAKA AKAN MENJALANKAN FUNGSI BERIKUT
        this.loadPostsData()
    },
    data() {
        return {
            //UNTUK VARIABLE FIELDS, DEFINISIKAN KEY UNTUK MASING-MASING DATA DAN SORTABLE BERNILAI TRUE JIKA INGIN MENAKTIFKAN FITUR SORTING DAN FALSE JIKA TIDAK INGIN MENGAKTIFKAN
            fields: [
                {key: 'sekolah.nama', 'label': 'Sekolah', sortable: true},
                {key: 'nama', 'label': 'Nama', sortable: true},
                {key: 'no_sertifikat_tanah', 'label': 'Nomor Sertifikat', sortable: true},
                {key: 'panjang', 'label': 'Panjang (m)', sortable: true},
                {key: 'lebar', 'label': 'Lebar (m)', sortable: true},
                {key: 'luas', 'label': 'Luas (m2)', sortable: true},
                {key: 'luas_lahan_tersedia', 'label': 'Luas Lahan Tersedia (m2)', sortable: true},
                {key: 'kepemilikan', 'label': 'Kepemilikan', sortable: true},
                {key: 'keterangan', 'label': 'Keterangan', sortable: true},
                {key: 'actions', 'label': 'Aksi', sortable: false}, //TAMBAHKAN CODE INI
            ],
            items: [], //DEFAULT VALUE DARI ITEMS ADALAH KOSONG
            meta: [], //JUGA BERLAKU UNTUK META
            current_page: 1, //DEFAULT PAGE YANG AKTIF ADA PAGE 1
            per_page: 10, //DEFAULT LOAD PERPAGE ADALAH 10
            search: '',
            sortBy: 'created_at', //DEFAULT SORTNYA ADALAH CREATED_AT
            sortByDesc: true, //ASCEDING
            isLoading: false,
            form: new Form({
                sekolah_id: '',
                nama: '',
                no_sertifikat_tanah: '',
                panjang: '',
                lebar: '',
                luas: '',
                luas_lahan_tersedia: '',
                kepemilikan: '',
                keterangan: '',
            }),
            data_sekolah: [],
        }
    },
    components: {
        'app-datatable': Datatable //REGISTER COMPONENT DATATABLE
    },
    methods: {
        loadPostsData() {
            let current_page = this.search == '' ? this.current_page:1
            //LAKUKAN REQUEST KE API UNTUK MENGAMBIL DATA POSTINGAN
            axios.get(`/api/referensi/tanah`, {
                //KIRIMKAN PARAMETER BERUPA PAGE YANG SEDANG DILOAD, PENCARIAN, LOAD PERPAGE DAN SORTING.
                params: {
                    page: current_page,
                    per_page: this.per_page,
                    q: this.search,
                    sortby: this.sortBy,
                    sortbydesc: this.sortByDesc ? 'DESC':'ASC'
                }
            })
            .then((response) => {
                //JIKA RESPONSENYA DITERIMA
                let getData = response.data.data
                this.items = getData.data //MAKA ASSIGN DATA POSTINGAN KE DALAM VARIABLE ITEMS
                //DAN ASSIGN INFORMASI LAINNYA KE DALAM VARIABLE META
                this.meta = {
                    total: getData.total,
                    current_page: getData.current_page,
                    per_page: getData.per_page,
                    from: getData.from,
                    to: getData.to
                }
            })
        },
        getSekolah() {
            axios.get(`/api/referensi/all-sekolah`)
            .then((response) => {
                //JIKA RESPONSENYA DITERIMA
                let getData = response.data.data
                //this.items = getData.data //MAKA ASSIGN DATA POSTINGAN KE DALAM VARIABLE ITEMS
                //DAN ASSIGN INFORMASI LAINNYA KE DALAM VARIABLE META
                this.data_sekolah = getData
            })
        },
        //JIKA ADA EMIT TERKAIT LOAD PERPAGE, MAKA FUNGSI INI AKAN DIJALANKAN
        handlePerPage(val) {
            this.per_page = val //SET PER_PAGE DENGAN VALUE YANG DIKIRIM DARI EMIT
            this.loadPostsData() //DAN REQUEST DATA BARU KE SERVER
        },
        //JIKA ADA EMIT PAGINATION YANG DIKIRIM, MAKA FUNGSI INI AKAN DIEKSEKUSI
        handlePagination(val) {
            this.current_page = val //SET CURRENT PAGE YANG AKTIF
            this.loadPostsData()
        },
        //JIKA ADA DATA PENCARIAN
        handleSearch(val) {
            this.search = val //SET VALUE PENCARIAN KE VARIABLE SEARCH
            this.loadPostsData() //REQUEST DATA BARU
        },
        //JIKA ADA EMIT SORT
        handleSort(val) {
            if(val.sortBy){
                this.sortBy = val.sortBy
                this.sortByDesc = val.sortDesc

                this.loadPostsData() //DAN LOAD DATA BARU BERDASARKAN SORT
            }
        },
        newModal(){
            this.editmode = false;
            this.form.reset();
            this.getSekolah();
            $('#modalAdd').modal('show');
        },
        insertData(){
            this.form.post('/api/referensi/simpan-tanah').then((response)=>{
                console.log(response);
                $('#modalAdd').modal('hide');
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
    }
}
</script>