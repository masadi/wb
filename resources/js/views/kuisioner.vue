<template>
    <div>
        <div class="content-header">
            <div class="card bg-secondary">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-9">
                            <h2>Perangkat Kuesioner</h2>
                            <h1>Pemetaan Penjaminan Mutu Pendidikan</h1>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box bg-info">
                            <div class="info-box-content">
                                <span class="info-box-text">Progres Pengisian Anda</span>
                                <span class="info-box-number">70%</span>

                                <div class="progress">
                                <div class="progress-bar" style="width: 70%"></div>
                                </div>
                                <span class="progress-description">
                                    <button class="btn btn-success btn-sm btn-block btn-flat" v-on:click="hitung_nilai_kuisioner">Hitung Nilai Kuisioner</button>
                                </span>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <app-instrumen :items="items" :fields="fields" :meta="meta" :editUrl="'/edit/'" :title="'Hapus Instrumen'" @per_page="handlePerPage" @pagination="handlePagination" @search="handleSearch" @sort="handleSort" @delete="handleDelete"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    // VueJS components will run here.
    import Instrumen from './components/Kuisioner.vue' //IMPORT COMPONENT DATATABLENYA
import axios from 'axios' //IMPORT AXIOS

export default {
    //props: ['user'],
    //KETIKA COMPONENT INI DILOAD
    created() {
        //MAKA AKAN MENJALANKAN FUNGSI BERIKUT
        this.loadPostsData()
    },
    data() {
        return {
            //UNTUK VARIABLE FIELDS, DEFINISIKAN KEY UNTUK MASING-MASING DATA DAN SORTABLE BERNILAI TRUE JIKA INGIN MENAKTIFKAN FITUR SORTING DAN FALSE JIKA TIDAK INGIN MENGAKTIFKAN
            fields: [
                {key: 'title', sortable: true},
                {key: 'author', sortable: true},
                {key: 'category', sortable: true},
                {key: 'created_at', sortable: true},
                {key: 'actions', sortable: false}, //TAMBAHKAN CODE INI
            ],
            items: [], //DEFAULT VALUE DARI ITEMS ADALAH KOSONG
            meta: [], //JUGA BERLAKU UNTUK META
            current_page: 1, //DEFAULT PAGE YANG AKTIF ADA PAGE 1
            per_page: 10, //DEFAULT LOAD PERPAGE ADALAH 10
            search: '',
            sortBy: 'created_at', //DEFAULT SORTNYA ADALAH CREATED_AT
            sortByDesc: false, //ASCEDING
        }
    },
    components: {
        'app-instrumen': Instrumen //REGISTER COMPONENT DATATABLE
    },
    methods: {
        hitung_nilai_kuisioner: function (event) {
      // `this` inside methods points to the Vue instance
            Swal.fire({
                title: 'Apakah Anda Yakin?',
                text: "Proses ini akan sedikit memakan waktu!",
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya!',
                cancelButtonText: 'Batal',
                showLoaderOnConfirm: true,
            }).then((result) => {
                if (result.value) {
                    axios.get(`/api/hitung-nilai-instrumen/${user.user_id}`).then(() => {
                        Swal.fire(
                            'Selesai',
                            'Hitung Nilai Instrumen Berhasil!',
                            'success'
                        ).then(() => {
                            this.loadPostsData();
                        })
                    }).catch((data)=> {
                        Swal.fire("Gagal!", data.message, "warning");
                    });
                }
            })
        },
        //METHOD INI AKAN MENGHANDLE REQUEST DATA KE API
        loadPostsData() {
            let current_page = this.search == '' ? this.current_page:1
            //LAKUKAN REQUEST KE API UNTUK MENGAMBIL DATA POSTINGAN
            axios.get(`/api/instrumen`, {
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
        deletePostData(id) {
            axios.delete(`/api/instrumen/${id}`).then(() => this.loadPostsData())
        },
        editPostData(id) {
            axios.get(`/api/instrumen/${id}`).then(() => this.loadPostsData())
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
        handleDelete(val) {
            this.deletePostData(val.id)
        }
    }
}
</script>