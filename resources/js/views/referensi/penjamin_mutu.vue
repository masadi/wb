<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Data Verifikator</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><router-link tag="a" to="/beranda">Beranda</router-link></li>
                            <li class="breadcrumb-item active">Data Verifikator</li>
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
                                    Data Verifikator
                                </h3>
                            </div>
                            <div class="card-body">
                                <app-datatable :items="items" :fields="fields" :meta="meta" :title="'Hapus Verifikator'" @per_page="handlePerPage" @pagination="handlePagination" @search="handleSearch" @sort="handleSort"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <my-loader/>
    </div>
</template>

<script>
    import Datatable from './../components/PenjaminMutu.vue' //IMPORT COMPONENT DATATABLENYA
    export default {
        data() {
            return {
                fields: [
                    {key: 'name', 'label': 'Nama Lengkap', sortable: true},
                    {key: 'nomor_hp', 'label': 'Nomor HP', sortable: true},
                    {key: 'email', 'label': 'Email', sortable: true},
                    {key: 'actions', 'label': 'Aksi', sortable: false}, //TAMBAHKAN CODE INI
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
                let current_page = this.search == '' ? this.current_page:1
                //LAKUKAN REQUEST KE API UNTUK MENGAMBIL DATA POSTINGAN
                axios.get(`/api/referensi/penjamin-mutu`, {
                    //KIRIMKAN PARAMETER BERUPA PAGE YANG SEDANG DILOAD, PENCARIAN, LOAD PERPAGE DAN SORTING.
                    params: {
                        sekolah_id: this.sekolah_id,
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
        }
    }

</script>
