<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Data Ruang</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><router-link tag="a" to="/beranda">Beranda</router-link></li>
                            <li class="breadcrumb-item active">Data Ruang</li>
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
                                <h3 class="card-title">List Ruang</h3>
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
                                <v-select label="nama" :options="data_sekolah" v-model="form.sekolah_id" @input="updateTanah" class="form-control" />
                                <has-error :form="form" field="sekolah_id"></has-error>
                            </div>
                            <div class="form-group">
                                <label>Tanah</label>
                                <v-select label="nama" :options="data_tanah" v-model="form.tanah_id" class="form-control" @input="updateBangunan" />
                                <has-error :form="form" field="tanah_id"></has-error>
                            </div>
                            <div class="form-group">
                                <label>Bangunan</label>
                                <v-select label="nama" :options="data_bangunan" v-model="form.bangunan_id" />
                                <has-error :form="form" field="bangunan_id"></has-error>
                            </div>
                            <div class="form-group">
                                <label>Kode Ruang</label>
                                <input v-model="form.kode" type="text" name="kode" class="form-control" :class="{ 'is-invalid': form.errors.has('kode') }">
                                <has-error :form="form" field="kode"></has-error>
                            </div>
                            <div class="form-group">
                                <label>Nama Ruang</label>
                                <input v-model="form.nama" type="text" name="nama" class="form-control" :class="{ 'is-invalid': form.errors.has('nama') }">
                                <has-error :form="form" field="nama"></has-error>
                            </div>
                            <div class="form-group">
                                <label>Registrasi Ruang</label>
                                <input v-model="form.registrasi" type="text" name="registrasi" class="form-control" :class="{ 'is-invalid': form.errors.has('registrasi') }">
                                <has-error :form="form" field="registrasi"></has-error>
                            </div>
                            <div class="form-group">
                                <label>Lantai Ke-</label>
                                <input v-model="form.lantai_ke" type="text" name="lebar" class="form-control" :class="{ 'is-invalid': form.errors.has('lantai_ke') }">
                                <has-error :form="form" field="lantai_ke"></has-error>
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
                                <label>Luas Plester (m<sup>2</sup>)</label>
                                <input v-model="form.luas_plester" type="text" name="luas_plester" class="form-control" :class="{ 'is-invalid': form.errors.has('luas_plester') }">
                                <has-error :form="form" field="luas_plester"></has-error>
                            </div>
                            <div class="form-group">
                                <label>Luas Plafon (m<sup>2</sup>)</label>
                                <input v-model="form.luas_plafon" type="text" name="luas_plafon" class="form-control" :class="{ 'is-invalid': form.errors.has('luas_plafon') }">
                                <has-error :form="form" field="luas_plafon"></has-error>
                            </div>
                            <div class="form-group">
                                <label>Luas dinding (m<sup>2</sup>)</label>
                                <input v-model="form.luas_dinding" type="text" name="luas_dinding" class="form-control" :class="{ 'is-invalid': form.errors.has('luas_dinding') }">
                                <has-error :form="form" field="luas_dinding"></has-error>
                            </div>

                            <div class="form-group">
                                <label>Luas Daun Jendela (m<sup>2</sup>)</label>
                                <input v-model="form.luas_daun_jendela" type="text" name="luas_daun_jendela" class="form-control" :class="{ 'is-invalid': form.errors.has('luas_daun_jendela') }">
                                <has-error :form="form" field="luas_daun_jendela"></has-error>
                            </div>
                            <div class="form-group">
                                <label>Luas Kusen (m<sup>2</sup>)</label>
                                <input v-model="form.luas_kusen" type="text" name="luas_kusen" class="form-control" :class="{ 'is-invalid': form.errors.has('luas_kusen') }">
                                <has-error :form="form" field="luas_kusen"></has-error>
                            </div>
                            <div class="form-group">
                                <label>Luas Tutup Lantai (m<sup>2</sup>)</label>
                                <input v-model="form.luas_tutup_lantai" type="text" name="luas_tutup_lantai" class="form-control" :class="{ 'is-invalid': form.errors.has('luas_tutup_lantai') }">
                                <has-error :form="form" field="luas_tutup_lantai"></has-error>
                            </div>
                            <div class="form-group">
                                <label>Jumlah Instalasi Listrik</label>
                                <input v-model="form.jumlah_instalasi_listrik" type="text" name="jumlah_instalasi_listrik" class="form-control" :class="{ 'is-invalid': form.errors.has('jumlah_instalasi_listrik') }">
                                <has-error :form="form" field="jumlah_instalasi_listrik"></has-error>
                            </div>
                            <div class="form-group">
                                <label>Panjang Instalasi Air (m)</label>
                                <input v-model="form.panjang_instalasi_air" type="text" name="panjang_instalasi_air" class="form-control" :class="{ 'is-invalid': form.errors.has('panjang_instalasi_air') }">
                                <has-error :form="form" field="panjang_instalasi_air"></has-error>
                            </div>
                            <div class="form-group">
                                <label>Jumlah Instalasi Air</label>
                                <input v-model="form.jumlah_instalasi_air" type="text" name="jumlah_instalasi_air" class="form-control" :class="{ 'is-invalid': form.errors.has('jumlah_instalasi_air') }">
                                <has-error :form="form" field="jumlah_instalasi_air"></has-error>
                            </div>
                            <div class="form-group">
                                <label>Panjang Drainase (m)</label>
                                <input v-model="form.panjang_drainase" type="text" name="panjang_drainase" class="form-control" :class="{ 'is-invalid': form.errors.has('panjang_drainase') }">
                                <has-error :form="form" field="panjang_drainase"></has-error>
                            </div>
                            <div class="form-group">
                                <label>Luas Finish Struktur (m<sup>2</sup>)</label>
                                <input v-model="form.luas_finish_struktur" type="text" name="luas_finish_struktur" class="form-control" :class="{ 'is-invalid': form.errors.has('luas_finish_struktur') }">
                                <has-error :form="form" field="luas_finish_struktur"></has-error>
                            </div>
                            <div class="form-group">
                                <label>Luas Finish Plafon (m<sup>2</sup>)</label>
                                <input v-model="form.luas_finish_plafon" type="text" name="luas_finish_plafon" class="form-control" :class="{ 'is-invalid': form.errors.has('luas_finish_plafon') }">
                                <has-error :form="form" field="luas_finish_plafon"></has-error>
                            </div>
                            <div class="form-group">
                                <label>Luas Finish Dinding (m<sup>2</sup>)</label>
                                <input v-model="form.luas_finish_dinding" type="text" name="luas_finish_dinding" class="form-control" :class="{ 'is-invalid': form.errors.has('luas_finish_dinding') }">
                                <has-error :form="form" field="luas_finish_dinding"></has-error>
                            </div>
                            <div class="form-group">
                                <label>Luas Finish KPJ (m<sup>2</sup>)</label>
                                <input v-model="form.luas_finish_kpj" type="text" name="luas_finish_kpj" class="form-control" :class="{ 'is-invalid': form.errors.has('luas_finish_kpj') }">
                                <has-error :form="form" field="luas_finish_kpj"></has-error>
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
    import Datatable from './../components/Ruang.vue' //IMPORT COMPONENT DATATABLENYA
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
                {key: 'bangunan.tanah.sekolah.nama', 'label': 'Sekolah', sortable: true},
                {key: 'bangunan.tanah.nama', 'label': 'Tanah', sortable: true},
                {key: 'bangunan.nama', 'label': 'Bangunan', sortable: true},
                {key: 'kode', 'label': 'Kode Ruang', sortable: true},
                {key: 'nama', 'label': 'Nama Ruang', sortable: true},
                {key: 'lantai_ke', 'label': 'Lantai Ke-', sortable: true},
                {key: 'bangunan.tanah.nama', 'label': 'Tanah', sortable: true},
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
                jenis_prasarana_id: '',
                bangunan_id: '',
                kode: '',
                nama: '',
                registrasi: '',
                lantai_ke: 1,
                panjang: 0,
                lebar: 0,
                luas: 0,
                luas_plester: 0,
                luas_plafon: 0,
                luas_dindik: 0,
                luas_daun_jendela: 0,
                luas_kusen: 0,
                luas_tutup_lantai: 0,
                jumlah_instalasi_listrik: 0,
                panjang_instalasi_air: 0,
                jumlah_instalasi_air: 0,
                panjang_drainase: 0,
                luas_finish_struktur: 0,
                luas_finish_plafon: 0,
                luas_finish_dindik: 0,
                luas_finish_kpj: 0,
                keterangan: '',
            }),
            data_sekolah: [],
            data_tanah: [],
            data_bangunan: [],
        }
    },
    components: {
        'app-datatable': Datatable //REGISTER COMPONENT DATATABLE
    },
    methods: {
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
        loadPostsData() {
            let current_page = this.search == '' ? this.current_page:1
            //LAKUKAN REQUEST KE API UNTUK MENGAMBIL DATA POSTINGAN
            axios.get(`/api/referensi/ruang`, {
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