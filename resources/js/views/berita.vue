<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Berita</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><router-link tag="a" to="/beranda">Beranda</router-link></li>
                            <li class="breadcrumb-item active">Berita</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">List Berita</h3>
                                <div class="card-tools">
                                    <button class="btn btn-success btn-sm btn-block btn-flat" v-on:click="newModal">Tambah Data</button>
                                </div>
                            </div>
                            <div class="card-body">
                                <app-berita :items="items" :fields="fields" :meta="meta" :title="'Hapus Berita'" @per_page="handlePerPage" @pagination="handlePagination" @search="handleSearch" @sort="handleSort"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modalAdd" tabindex="-1" role="dialog" aria-labelledby="modalAdd" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Berita</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form @submit.prevent="insertData()">
                    <div class="modal-body">
                        <input v-model="form.user_id" type="hidden" name="user_id" id="user_id">
                        <div class="form-group">
                            <label>Judul</label>
                            <input v-model="form.judul" type="text" name="judul"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('judul') }">
                            <has-error :form="form" field="judul"></has-error>
                        </div>
                        <div class="form-group">
                            <label>Kategori</label>
                            <multiselect v-model="form.kategori" :options="all_kategori" :multiple="true" :close-on-select="false" :clear-on-select="false" :preserve-search="true" placeholder="== Pilih Kategori ==" label="nama" track-by="nama" :preselect-first="true" :class="{ 'is-invalid': form.errors.has('kategori') }" @open="allKategori">
                                <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} kategori terpilih</span></template>
                            </multiselect>
                            <has-error :form="form" field="kategori"></has-error>
                        </div>
                    
                        <div class="form-group">
                            <label>Isi Berita</label>
                            <textarea v-model="form.isi_berita" name="isi_berita"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('isi_berita') }" autocomplete="false">
                            </textarea>
                            <has-error :form="form" field="isi_berita"></has-error>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button v-show="editmode" type="submit" class="btn btn-success">Perbaharui</button>
                        <button v-show="!editmode" type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                  </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    // VueJS components will run here.
    import Berita from './components/Berita.vue' //IMPORT COMPONENT DATATABLENYA
    import Multiselect from 'vue-multiselect'
    import axios from 'axios' //IMPORT AXIOS

export default {
    //KETIKA COMPONENT INI DILOAD
    created() {
        //MAKA AKAN MENJALANKAN FUNGSI BERIKUT
        this.loadPostsData()
    },
    data() {
        return {
            editmode: false,
            form: new Form({
                id : '',
                user_id: '',
                judul : '',
                isi_berita: '',
                kategori: '',
            }),
            //UNTUK VARIABLE FIELDS, DEFINISIKAN KEY UNTUK MASING-MASING DATA DAN SORTABLE BERNILAI TRUE JIKA INGIN MENAKTIFKAN FITUR SORTING DAN FALSE JIKA TIDAK INGIN MENGAKTIFKAN
            fields: [
                {key: 'judul', sortable: true},
                {key: 'penulis', sortable: true},
                {key: 'kategori', sortable: true},
                {key: 'status', sortable: true},
                {key: 'actions', sortable: false}, //TAMBAHKAN CODE INI
            ],
            items: [], //DEFAULT VALUE DARI ITEMS ADALAH KOSONG
            meta: [], //JUGA BERLAKU UNTUK META
            current_page: 1, //DEFAULT PAGE YANG AKTIF ADA PAGE 1
            per_page: 10, //DEFAULT LOAD PERPAGE ADALAH 10
            search: '',
            sortBy: 'created_at', //DEFAULT SORTNYA ADALAH CREATED_AT
            sortByDesc: true, //ASCEDING
            selectedKategori: [],
            all_kategori: [],
        }
    },
    components: {
        Multiselect,
        'app-berita': Berita //REGISTER COMPONENT DATATABLE
    },
    methods: {
        allKategori(){
            axios.get(`/api/get-kategori`).then(response => {
                console.log(response.data.data)
                this.all_kategori = response.data.data
                this.isLoading = false
            })
        },
        //METHOD INI AKAN MENGHANDLE REQUEST DATA KE API
        loadPostsData() {
            let current_page = this.search == '' ? this.current_page:1
            //LAKUKAN REQUEST KE API UNTUK MENGAMBIL DATA POSTINGAN
            axios.get(`/api/berita`, {
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
            //MAKA SET SORT-NYA
            this.sortBy = val.sortBy
            this.sortByDesc = val.sortDesc

            this.loadPostsData() //DAN LOAD DATA BARU BERDASARKAN SORT
        },
        newModal(){
            this.editmode = false;
            this.form.reset();
            this.form.user_id = user.user_id;
            console.log(this.form);
            $('#modalAdd').modal('show');
        },
        insertData(){
            this.form.post('/api/berita').then((response)=>{
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
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>