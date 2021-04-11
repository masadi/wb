<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Data Alat</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><router-link tag="a" to="/beranda">Beranda</router-link></li>
                            <li class="breadcrumb-item active">Data Alat</li>
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
                                <h3 class="card-title">List Alat</h3>
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
                        <h5 class="modal-title">Tambah Data Alat</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="insertData()" method="post">
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Sekolah</label>
                                <v-select label="nama" :options="data_sekolah" v-model="form.sekolah_id" @input="updateTanah" />
                                <has-error :form="form" field="sekolah_id"></has-error>
                            </div>
                            <div class="form-group">
                                <label>Tanah</label>
                                <v-select label="nama" :options="data_tanah" v-model="form.tanah_id" @input="updateBangunan" />
                                <has-error :form="form" field="tanah_id"></has-error>
                            </div>
                            <div class="form-group">
                                <label>Bangunan</label>
                                <v-select label="nama" :options="data_bangunan" v-model="form.bangunan_id" @input="updateRuang" />
                                <has-error :form="form" field="bangunan_id"></has-error>
                            </div>
                            <div class="form-group">
                                <label>Ruang</label>
                                <v-select label="nama" :options="data_ruang" v-model="form.ruang_id" @input="updateJenis" />
                                <has-error :form="form" field="ruang_id"></has-error>
                            </div>
                            <div class="form-group">
                                <label>Jenis Sarana</label>
                                <v-select label="nama" :options="data_jenis" v-model="form.jenis_sarana_id" />
                                <has-error :form="form" field="jenis_sarana_id"></has-error>
                            </div>
                            <div class="form-group">
                                <label>Nama</label>
                                <input v-model="form.nama" type="text" name="nama" class="form-control" :class="{ 'is-invalid': form.errors.has('nama') }">
                                <has-error :form="form" field="nama"></has-error>
                            </div>
                            <div class="form-group">
                                <label>Spesifikasi</label>
                                <input v-model="form.spesifikasi" type="text" name="spesifikasi" class="form-control" :class="{ 'is-invalid': form.errors.has('spesifikasi') }">
                                <has-error :form="form" field="spesifikasi"></has-error>
                            </div>
                            <div class="form-group">
                                <label>Kepemilikan</label>
                                <v-select label="nama" :options="data_kepemilikan" v-model="form.kepemilikan_sarpras_id" />
                                <has-error :form="form" field="kepemilikan_sarpras_id"></has-error>
                            </div>
                            <div class="form-group">
                                <label>Keterangan</label>
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
    import Datatable from './../components/Alat.vue' //IMPORT COMPONENT DATATABLENYA
    import axios from 'axios' //IMPORT AXIOS
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
                {key: 'ruang.bangunan.tanah.sekolah.nama', 'label': 'Nama Sekolah', sortable: true},
                {key: 'ruang.nama', 'label': 'Nama Ruang', sortable: true},
                {key: 'jenis_sarana.nama', 'label': 'Jenis Sarana', sortable: true},
                {key: 'nama', 'label': 'Nama Alat', sortable: true},
                {key: 'spesifikasi', 'label': 'Spesifikasi', sortable: true},
                {key: 'kepemilikan.nama', 'label': 'Kepemilikan', sortable: true},
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
                tanah_id: '',
                bangunan_id: '',
                ruang_id: '',
                jenis_sarana_id: '',
                nama: '',
                spesifikasi: '',
                registrasi: '',
                kepemilikan_sarpras_id: '',
                keterangan: '',
            }),
            data_sekolah: [],
            data_tanah: [],
            data_bangunan: [],
            data_jenis: [],
            data_ruang: [],
            data_kepemilikan: [],
        }
    },
    components: {
        'app-datatable': Datatable //REGISTER COMPONENT DATATABLE
    },
    methods: {
        updateRuang(data){
            axios.get(`/api/referensi/all-ruang`, {
                //KIRIMKAN PARAMETER BERUPA PAGE YANG SEDANG DILOAD, PENCARIAN, LOAD PERPAGE DAN SORTING.
                params: {
                    bangunan_id: data.bangunan_id,
                }
            })
            .then((response) => {
                let getData = response.data.data
                this.data_ruang = getData
            })
        },
        updateBangunan(data){
            axios.get(`/api/referensi/all-bangunan`, {
                //KIRIMKAN PARAMETER BERUPA PAGE YANG SEDANG DILOAD, PENCARIAN, LOAD PERPAGE DAN SORTING.
                params: {
                    tanah_id: data.tanah_id,
                }
            })
            .then((response) => {
                let getData = response.data.data
                this.data_bangunan = getData
            })
        },
        updateJenis(){
            axios.get(`/api/referensi/all-jenis-sarana`, {
                //KIRIMKAN PARAMETER BERUPA PAGE YANG SEDANG DILOAD, PENCARIAN, LOAD PERPAGE DAN SORTING.
                params: {
                    data: 'a_alat',
                }
            })
            .then((response) => {
                let getData = response.data.data
                this.data_jenis = getData
            })
        },
        updateTanah(data){
            axios.get(`/api/referensi/all-tanah`, {
                //KIRIMKAN PARAMETER BERUPA PAGE YANG SEDANG DILOAD, PENCARIAN, LOAD PERPAGE DAN SORTING.
                params: {
                    sekolah_id: data.sekolah_id,
                }
            })
            .then((response) => {
                let getData = response.data.data
                this.data_tanah = getData
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
        getKepemilikan(){
            axios.get(`/api/referensi/kepemilikan`)
            .then((response) => {
                //JIKA RESPONSENYA DITERIMA
                let getData = response.data.data
                //this.items = getData.data //MAKA ASSIGN DATA POSTINGAN KE DALAM VARIABLE ITEMS
                //DAN ASSIGN INFORMASI LAINNYA KE DALAM VARIABLE META
                this.data_kepemilikan = getData
            })
        },
        loadPostsData() {
            let current_page = this.search == '' ? this.current_page:1
            //LAKUKAN REQUEST KE API UNTUK MENGAMBIL DATA POSTINGAN
            axios.get(`/api/referensi/alat`, {
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
            this.getKepemilikan();
            $('#modalAdd').modal('show');
        },
        insertData(){
            this.form.post('/api/referensi/simpan-alat').then((response)=>{
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