<template>
<div>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Data Komisi SUB IB</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <router-link tag="a" to="/beranda">Beranda</router-link>
                        </li>
                        <li class="breadcrumb-item active">Data Komisi SUB IB</li>
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
                                Data Trader
                            </h3>
                        </div>
                        <div class="card-body">
                            <app-datatable :items="items" :fields="fields" :meta="meta" :title="'Hapus Rebate Trader'" @per_page="handlePerPage" @pagination="handlePagination" @search="handleSearch" @sort="handleSort" />
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
                    <h5 class="modal-title">Tambah Sekolah</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="insertData()" method="post">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>NPSN</label>
                            <input v-model="form.npsn" type="text" name="npsn" class="form-control" :class="{ 'is-invalid': form.errors.has('npsn') }">
                            <has-error :form="form" field="npsn"></has-error>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Sinkronisasi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <my-loader />
</div>
</template>

<script>
import Datatable from './components/Trader.vue' //IMPORT COMPONENT DATATABLENYA
import axios from 'axios' //IMPORT AXIOS
export default {
    //KETIKA COMPONENT INI DILOAD
    created() {
        //MAKA AKAN MENJALANKAN FUNGSI BERIKUT
        this.loadPostsData()
    },
    data() {
        return {
            user: user,
            fields: [{
                    key: 'tanggal_upload',
                    label: 'Periode Tanggal',
                    sortable: true
                },
                {
                    key: 'trader.nama_lengkap',
                    label: 'Nama Lengkap',
                    sortable: true
                },
                {
                    key: 'trader.nomor_akun',
                    label: 'Nomor Akun',
                    sortable: true
                },
                {
                    key: 'trader.email',
                    label: 'Email',
                    sortable: true
                },
                {
                    key: 'volume_trading',
                    label: 'Volume Trading',
                    sortable: false
                },
                {
                    key: 'jumlah_rebate',
                    label: 'Jumlah Rebate',
                    sortable: true
                },
                {
                    key: 'trader.bank',
                    label: 'Bank',
                    sortable: true
                },
                {
                    key: 'trader.nomor_rekening',
                    label: 'Nomor Rekening',
                    sortable: true
                },
                {
                    key: 'upline.nama',
                    label: 'SUB IB',
                    sortable: false
                },
            ],
            items: [], //DEFAULT VALUE DARI ITEMS ADALAH KOSONG
            meta: [], //JUGA BERLAKU UNTUK META
            current_page: 1, //DEFAULT PAGE YANG AKTIF ADA PAGE 1
            per_page: 10, //DEFAULT LOAD PERPAGE ADALAH 10
            search: '',
            sortBy: 'nama_lengkap', //DEFAULT SORTNYA ADALAH CREATED_AT
            sortByDesc: true, //ASCEDING
            form: new Form({
                npsn: '',
            }),
        }
    },
    components: {
        'app-datatable': Datatable //REGISTER COMPONENT DATATABLE
    },
    methods: {
        newModal() {
            this.editmode = false;
            this.form.reset();
            this.form.user_id = user.user_id;
            $('#modalAdd').modal('show');
        },
        loadPostsData() {
            let current_page = this.search == '' ? this.current_page : 1
            //LAKUKAN REQUEST KE API UNTUK MENGAMBIL DATA POSTINGAN
            axios.get(`/api/transaksi/komisi`, {
                    //KIRIMKAN PARAMETER BERUPA PAGE YANG SEDANG DILOAD, PENCARIAN, LOAD PERPAGE DAN SORTING.
                    params: {
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
                        to: getData.to,
                        isBusy: false,
                    }
                    console.log(getData.data)
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
        insertData() {
            this.form.post('/api/sinkronisasi').then((response) => {
                //console.log(response);
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
