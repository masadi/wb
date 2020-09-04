<template>
    <div>
        <app-datatable :permintaan="permintaan" :verifikator_id="verifikator_id" :items="items" :fields="fields" :meta="meta" :title="'Hapus Sekolah'" @per_page="handlePerPage" @pagination="handlePagination" @search="handleSearch" @sort="handleSort"/>
    </div>
</template>
<script>
    import Datatable from './components/SekolahSasaran.vue' //IMPORT COMPONENT DATATABLENYA
    import axios from 'axios' //IMPORT AXIOS
export default {
    //KETIKA COMPONENT INI DILOAD
    props: {
        verifikator_id: {
            type: String,
            default: "Delete Modal"
        },
        permintaan : {
            type: String,
            default: "-"
        },
    },
    created() {
        //MAKA AKAN MENJALANKAN FUNGSI BERIKUT
        this.loadPostsData()
    },
    data() {
        return {
            fields: [
                {key: 'nama', 'label': 'Nama Sekolah', sortable: true},
                {key: 'npsn', 'label': 'NPSN', sortable: true},
                {key: 'kecamatan', 'label': 'Kecamatan', sortable: true},
                {key: 'kabupaten', 'label': 'Kabupaten/Kota', sortable: true},
                {key: 'provinsi', 'label': 'Provinsi', sortable: true},
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
    components: {
        'app-datatable': Datatable,
    },
    methods: {
        hideModal(){
            this.$refs.entahlah.hide();
        },
        loadPostsData() {
            let current_page = this.search == '' ? this.current_page:1
            //LAKUKAN REQUEST KE API UNTUK MENGAMBIL DATA POSTINGAN
            axios.get(`/api/referensi/sekolah-sasaran`, {
                //KIRIMKAN PARAMETER BERUPA PAGE YANG SEDANG DILOAD, PENCARIAN, LOAD PERPAGE DAN SORTING.
                params: {
                    permintaan: this.permintaan,
                    verifikator_id: this.verifikator_id,
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
