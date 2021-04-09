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
                    <h5 class="modal-title">Tambah Buku</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form @submit.prevent="insertData()" method="post">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nama Lengkap</label>
                            <input v-model="form.id" type="hidden" name="id" class="form-control" :class="{ 'is-invalid': form.errors.has('id') }">
                            <input v-model="form.nama" type="text" name="nama" class="form-control" :class="{ 'is-invalid': form.errors.has('nama') }">
                            <has-error :form="form" field="nama"></has-error>
                        </div>
                        <div class="form-group">
                            <label>NIP</label>
                            <input v-model="form.nip" type="text" name="nip" class="form-control" :class="{ 'is-invalid': form.errors.has('nip') }">
                            <has-error :form="form" field="nip"></has-error>
                        </div>
                        <div class="form-group">
                            <label>NUPTK</label>
                            <input v-model="form.nuptk" type="text" name="nuptk" class="form-control" :class="{ 'is-invalid': form.errors.has('nuptk') }">
                            <has-error :form="form" field="nuptk"></has-error>
                        </div>
                        <div class="form-group">
                            <label>Asal Instansi</label>
                            <input v-model="form.instansi" type="text" name="instansi" class="form-control" :class="{ 'is-invalid': form.errors.has('instansi') }">
                            <has-error :form="form" field="instansi"></has-error>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input v-model="form.email" type="text" name="email" class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
                            <has-error :form="form" field="email"></has-error>
                        </div>
                        <div class="form-group">
                            <label>Nomor Handphone</label>
                            <input v-model="form.nomor_hp" type="text" name="nomor_hp" class="form-control" :class="{ 'is-invalid': form.errors.has('nomor_hp') }">
                            <has-error :form="form" field="nomor_hp"></has-error>
                        </div>
                        <div class="form-group">
                            <label>Token</label>
                            <input v-model="form.token" type="text" name="token" class="form-control" :class="{ 'is-invalid': form.errors.has('token') }">
                            <has-error :form="form" field="token"></has-error>
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
                nama: '',
                nip: '',
                nuptk: '',
                instansi: '',
                email: '',
                nomor_hp: '',
                token: '',
            }),
            fields: [{
                    key: 'nama',
                    'label': 'Nama Lengkap',
                    sortable: true
                },
                {
                    key: 'instansi',
                    'label': 'Instansi',
                    sortable: true
                },
                {
                    key: 'jml_sekolah',
                    'label': 'Jml Sekolah',
                    sortable: true
                },
                {
                    key: 'actions',
                    'label': 'Aksi',
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
            sekolah_id: user.sekolah_id,
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
            this.form.user_id = user.user_id;
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
