<template>
<div>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Data Buku</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <router-link tag="a" to="/beranda">Beranda</router-link>
                        </li>
                        <li class="breadcrumb-item active">Data Buku</li>
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
                                <i class="fas fa-th mr-1"></i>
                                Data Buku
                            </h3>
                            <div class="card-tools" v-show="hasRole('admin')">
                                <button class="btn btn-success btn-sm btn-block btn-flat" v-on:click="newModal">Tambah Data</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <app-datatable :items="items" :fields="fields" :meta="meta" :title="'Hapus Pendamping'" @per_page="handlePerPage" @pagination="handlePagination" @search="handleSearch" @sort="handleSort" />
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
                        <h5 class="modal-title">Tambah Data Buku</h5>
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
                                <label>Kelas</label>
                                <v-select label="nama" :options="data_kelas" v-model="form.kelas" @input="updateMapel" />
                                <has-error :form="form" field="kelas"></has-error>
                            </div>
                            <div class="form-group">
                                <label>Mata Pelajaran</label>
                                <v-select label="nama" :options="data_mapel" v-model="form.mata_pelajaran_id" />
                                <has-error :form="form" field="mata_pelajaran_id"></has-error>
                            </div>
                            <div class="form-group">
                                <label>Kode Buku</label>
                                <input v-model="form.kode" type="text" name="kode" class="form-control" :class="{ 'is-invalid': form.errors.has('kode') }">
                                <has-error :form="form" field="kode"></has-error>
                            </div>
                            <div class="form-group">
                                <label>Judul Buku</label>
                                <input v-model="form.judul" type="text" name="judul" class="form-control" :class="{ 'is-invalid': form.errors.has('judul') }">
                                <has-error :form="form" field="judul"></has-error>
                            </div>
                            <div class="form-group">
                                <label>Nama Penerbit</label>
                                <input v-model="form.nama_penerbit" type="text" name="nama_penerbit" class="form-control" :class="{ 'is-invalid': form.errors.has('nama_penerbit') }">
                                <has-error :form="form" field="nama_penerbit"></has-error>
                            </div>
                            <div class="form-group">
                                <label>ISBN/ISSN</label>
                                <input v-model="form.isbn_issn" type="text" name="isbn_issn" class="form-control" :class="{ 'is-invalid': form.errors.has('isbn_issn') }">
                                <has-error :form="form" field="isbn_issn"></has-error>
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
    <my-loader />
</div>
</template>

<script>
import Datatable from './../components/Buku.vue' //IMPORT COMPONENT DATATABLENYA
export default {
    data() {
        return {
            editmode: false,
            form: new Form({
                id: '',
                sekolah_id: '',
                kode: '',
                judul: '',
                mata_pelajaran_id: '',
                nama_penerbit: '',
                isbn_issn: '',
                keterangan: '',
                kelas: '',
            }),
            fields: [{
                    key: 'sekolah.nama',
                    label: 'Sekolah',
                    sortable: true
                },
                {
                    key: 'kode',
                    label: 'Kode Buku',
                    sortable: true
                },
                {
                    key: 'judul',
                    label: 'Judul Buku',
                    sortable: true
                },
                {
                    key: 'mata_pelajaran.nama',
                    label: 'Mata Pelajaran',
                    sortable: true
                },
                {
                    key: 'kelas',
                    sortable: true
                },
                {
                    key: 'nama_penerbit',
                    sortable: true
                },
                {
                    key: 'isbn_issn',
                    label: 'ISBN/ISSN',
                    sortable: true
                },
                {
                    key: 'actions',
                    label: 'Aksi',
                    sortable: false
                }, //TAMBAHKAN CODE INI
            ],
            items: [], //DEFAULT VALUE DARI ITEMS ADALAH KOSONG
            meta: [], //JUGA BERLAKU UNTUK META
            current_page: 1, //DEFAULT PAGE YANG AKTIF ADA PAGE 1
            per_page: 10, //DEFAULT LOAD PERPAGE ADALAH 10
            search: '',
            sortBy: 'created_at', //DEFAULT SORTNYA ADALAH CREATED_AT
            sortByDesc: true, //ASCEDING
            data_sekolah: [],
            data_mapel: [],
            data_kelas: [7, 8, 9],
        }
    },
    created() {
        //axios.get('/api/users').then(({data}) => this.users = data);
        this.loadPostsData()
    },
    components: {
        'app-datatable': Datatable //REGISTER COMPONENT DATATABLE
    },
    methods: {
        updateMapel(data){
            console.log(data);
            axios.get(`/api/referensi/all-mapel`, {
                //KIRIMKAN PARAMETER BERUPA PAGE YANG SEDANG DILOAD, PENCARIAN, LOAD PERPAGE DAN SORTING.
                params: {
                    tingkat_pendidikan_id: data,
                }
            })
            .then((response) => {
                //JIKA RESPONSENYA DITERIMA
                let getData = response.data.data
                this.data_mapel = getData
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
        loadPostsData() {
            let current_page = this.search == '' ? this.current_page : 1
            //LAKUKAN REQUEST KE API UNTUK MENGAMBIL DATA POSTINGAN
            axios.get(`/api/referensi/buku`, {
                    //KIRIMKAN PARAMETER BERUPA PAGE YANG SEDANG DILOAD, PENCARIAN, LOAD PERPAGE DAN SORTING.
                    params: {
                        sekolah_id: this.sekolah_id,
                        page: current_page,
                        per_page: this.per_page,
                        q: this.search,
                        sortby: this.sortBy,
                        sortbydesc: this.sortByDesc ? 'DESC' : 'ASC'
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
            if (val.sortBy) {
                this.sortBy = val.sortBy
                this.sortByDesc = val.sortDesc

                this.loadPostsData() //DAN LOAD DATA BARU BERDASARKAN SORT
            }
        },
        newModal() {
            this.editmode = false;
            this.form.reset();
            this.getSekolah()
            $('#modalAdd').modal('show');
        },
        insertData() {
            this.form.post('/api/referensi/simpan-buku').then((response) => {
                console.log(response);
                $('#modalAdd').modal('hide');
                Toast.fire({
                    icon: 'success',
                    title: response.message
                });
                this.loadPostsData();
            }).catch((e) => {
                Toast.fire({
                    icon: 'error',
                    title: 'Some error occured! Please try again'
                });
            })
        },
    }
}
</script>
