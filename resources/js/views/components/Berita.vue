<template>
    <div>
        <div class="row">
      	<!-- BLOCK INI AKAN MENGHANDLE LOAD DATA PERPAGE, DENGAN DEFAULT ADALAH 10 DATA -->
        <div class="col-md-4 mb-2">
            <div class="form-inline">
                <!-- KETIKA SELECT BOXNYA DIGANTI, MAKA AKAN MENJALANKAN FUNGSI loadPerPage -->
                <select class="form-control" v-model="meta.per_page" @change="loadPerPage">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <label class="ml-2">Entri</label>
            </div>
        </div>
      
        <!-- BLOCK INI AKAN MENG-HANDLE PENCARIAN DATA -->
        <div class="col-md-4 offset-md-4">
            <div class="form-inline float-right">
                <label class="mr-2">Search</label>
                <!-- KETIKA ADA INPUTAN PADA KOLOM PENCARIAN, MAKA AKAN MENJALANKAN FUNGSI SEARCH -->
                <input type="text" class="form-control" @input="search">
            </div>
        </div>
      </div>
      	<!-- BLOCK INI AKAN MENGHASILKAN LIST DATA DALAM BENTUK TABLE MENGGUNAKAN COMPONENT TABLE DARI BOOTSTRAP VUE -->
        
            <!-- :ITEMS ADALAH DATA YANG AKAN DITAMPILKAN -->
            <!-- :FIELDS AKAN MENJADI HEADER DARI TABLE, MAKA BERISI FIELD YANG SALING BERKORELASI DENGAN ITEMS -->
            <!-- :sort-by.sync & :sort-desc.sync AKAN MENGHANDLE FITUR SORTING -->
            <b-table striped hover :items="items" :fields="fields" :sort-by.sync="sortBy" :sort-desc.sync="sortDesc" show-empty>
                <template v-slot:cell(status)="row">
                    <span class="btn btn-sm btn-success btn-flat">Terpublikasi</span>
                </template>
                <template v-slot:cell(penulis)="row">
                    {{row.item.user.name}}
                </template>
                <template v-slot:cell(kategori)="row">
                    <span v-for="(list, index) in row.item.kategori">
                        <span>{{list.nama}}</span><span v-if="index+1 < row.item.kategori.length">, </span>
                    </span>
                </template> 
                <template v-slot:cell(actions)="row">
                    <b-dropdown id="dropdown-dropleft" dropleft text="Aksi" variant="success">
                        <b-dropdown-item href="javascript:" @click="openShowModal(row)"><i class="fas fa-search"></i> Detil</b-dropdown-item>
                        <b-dropdown-item href="javascript:" @click="openEditModal(row)"><i class="fas fa-edit"></i> Edit</b-dropdown-item>
                        <b-dropdown-item href="javascript:" @click="openDeleteModal(row)"><i class="fas fa-trash"></i> Hapus</b-dropdown-item>
                    </b-dropdown>
                </template>
            </b-table>   
      
      	<!-- BAGIAN INI AKAN MENAMPILKAN JUMLAH DATA YANG DI-LOAD -->
          <div class="row">
        <div class="col-md-6">
            <p>Showing {{ meta.from }} to {{ meta.to }} of {{ meta.total }} items</p>
        </div>
      
      	<!-- BLOCK INI AKAN MENJADI PAGINATION DARI DATA YANG DITAMPILKAN -->
        <div class="col-md-6">
          	<!-- DAN KETIKA TERJADI PERGANTIAN PAGE, MAKA AKAN MENJALANKAN FUNGSI changePage -->
            <b-pagination
                v-model="meta.current_page"
                :total-rows="meta.total"
                :per-page="meta.per_page"
                align="right"
                @change="changePage"
                aria-controls="dw-datatable"
            ></b-pagination>
        </div>
        </div>
        <b-modal v-model="deleteModal" :title="title">
            <p>Kamu yakin ingin menghapus data ini?</p>
            <template v-slot:modal-footer>
                <div class="w-100 float-right">
                    <b-button
                        variant="secondary"
                        size="sm"
                        @click="deleteModal=false"
                    >
                        Batal
                    </b-button>
                    <!-- JIKA TOMBOL DELETE DITEKAN, MAKA FUNGSI deleteModalButton AKAN DIJALANKAN -->
                    <b-button
                        variant="danger"
                        size="sm"
                        @click="deleteModalButton"
                    >
                        Hapus
                    </b-button>
                </div>
            </template>
        </b-modal>
        <b-modal id="modal-xl" size="xl" v-model="showModal" title="Detil Berita">
            {{ modalText }}
            <template v-slot:modal-footer>
                <div class="w-100 float-right">
                    <b-button
                        variant="secondary"
                        size="sm"
                        @click="showModal=false"
                    >
                        Tutup
                    </b-button>
                </div>
            </template>
        </b-modal>
        <b-modal id="modal-prevent-closing" size="xl" v-model="editModal" title="Edit Instrumen" @show="resetModal"
      @hidden="resetModal"
      @ok="handleOk">
            <form ref="form" @submit.stop.prevent="handleSubmit">
        <b-form-group
          :state="judul"
          label="Judul"
          label-for="judul"
          invalid-feedback="Name is required"
        >
          <b-form-input
            id="judul"
            v-model="judul"
            :state="judul"
            required
            value="judul"
          ></b-form-input>
        </b-form-group>
      </form>
      <template v-slot:modal-footer="{ ok, cancel, hide }">
            <!--b>Custom Footer</b-->
            <!-- Emulate built in modal footer ok and cancel button actions -->
            <b-button size="sm" variant="success" @click="ok()">
                Simpan
            </b-button>
            <b-button size="sm" variant="outline-secondary" @click="hide('forget')">
                Batal
            </b-button>
        </template>
        </b-modal>
    </div>
</template>

<script>
import _ from 'lodash' //IMPORT LODASH, DIMANA AKAN DIGUNAKAN UNTUK MEMBUAT DELAY KETIKA KOLOM PENCARIAN DIISI

export default {
    //PROPS INI ADALAH DATA YANG AKAN DIMINTA DARI PENGGUNA COMPONENT DATATABLE YANG KITA BUAT
    props: {
        //ITEMS STRUKTURNYA ADALAH ARRAY, KARENA BAGIAN INI BERISI DATA YANG AKAN DITAMPILKAN DAN SIFATNYA WAJIB DIKIRIMKAN KETIKA COMPONENT INI DIGUNAKAN
        items: {
            type: Array,
            required: true
        },
        //FIELDS JUGA SAMA DENGAN ITEMS
        fields: {
            type: Array,
            required: true
        },
        //ADAPUN META, TYPENYA ADALAH OBJECT YANG BERISI INFORMASI MENGENAL CURRENT PAGE, LOAD PERPAGE, TOTAL DATA, DAN LAIN SEBAGAINYA.
        meta: {
            required: true
        },
        title: {
            type: String,
            default: "Delete Modal"
        },
        editUrl: {
            type: String,
            default: null
        }
    },
    data() {
        return {
            name: '',
            judul: '',
            submittedNames: [],
            //VARIABLE INI AKAN MENGHADLE SORTING DATA
            sortBy: null, //FIELD YANG AKAN DISORT AKAN OTOMATIS DISIMPAN DISINI
            sortDesc: false, //SEDANGKAN JENISNYA ASCENDING ATAU DESC AKAN DISIMPAN DISINI
            //TAMBAHKAN DUA VARIABLE INI UNTUK MENGHANDLE MODAL DAN DATA YANG AKAN DIHAPUS
            deleteModal: false,
            showModal: false,
            editModal: false,
            modalText: '',
            selected: null 
        }
    },
    watch: {
        //KETIKA VALUE DARI VARIABLE sortBy BERUBAH
        sortBy(val) {
            //MAKA KITA EMIT DENGAN NAMA SORT DAN DATANYA ADALAH OBJECT BERUPA VALUE DARI SORTBY DAN SORTDESC
            //EMIT BERARTI MENGIRIMKAN DATA KEPADA PARENT ATAU YANG MEMANGGIL COMPONENT INI
            //SEHINGGA DARI PARENT TERSEBUT, KITA BISA MENGGUNAKAN VALUE YANG DIKIRIMKAN
            this.$emit('sort', {
                sortBy: this.sortBy,
                sortDesc: this.sortDesc
            })
        },
        //KETIKA VALUE DARI SORTDESC BERUBAH
        sortDesc(val) {
            //MAKA CARA YANG SAMA AKAN DIKERJAKAN
            this.$emit('sort', {
                sortBy: this.sortBy,
                sortDesc: this.sortDesc
            })
        }
    },
    methods: {
        //JIKA SELECT BOX DIGANTI, MAKA FUNGSI INI AKAN DIJALANKAN
        loadPerPage(val) {
            //DAN KITA EMIT LAGI DENGAN NAMA per_page DAN VALUE SESUAI PER_PAGE YANG DIPILIH
            this.$emit('per_page', this.meta.per_page)
        },
        //KETIKA PAGINATION BERUBAH, MAKA FUNGSI INI AKAN DIJALANKAN
        changePage(val) {
            //KIRIM EMIT DENGAN NAMA PAGINATION DAN VALUENYA ADALAH HALAMAN YANG DIPILIH OLEH USER
            this.$emit('pagination', val)
        },
        //KETIKA KOTAK PENCARIAN DIISI, MAKA FUNGSI INI AKAN DIJALANKAN
        //KITA GUNAKAN DEBOUNCE UNTUK MEMBUAT DELAY, DIMANA FUNGSI INI AKAN DIJALANKAN
        //500 MIL SECOND SETELAH USER BERHENTI MENGETIK
        search: _.debounce(function (e) {
            //KIRIM EMIT DENGAN NAMA SEARCH DAN VALUE SESUAI YANG DIKETIKKAN OLEH USER
            this.$emit('search', e.target.value)
        }, 500),
        openDeleteModal(row) {
            this.deleteModal = true
            this.selected = row.item
        },
        deleteModalButton() {
            this.$emit('delete', this.selected)
            this.deleteModal = false
        },
        openShowModal(row) {
            this.showModal = true
            this.modalText = row.item.isi_berita
            this.selected = row.item
        },
        openEditModal(row) {
            this.editModal = true
            this.judul = row.item.judul
        },
        resetModal() {
            this.name = ''
            this.judul = ''
        },
        handleOk(bvModalEvt) {
            // Prevent modal from closing
            bvModalEvt.preventDefault()
            // Trigger submit handler
            this.handleSubmit()
        },
        handleSubmit() {
            // Exit when the form isn't valid
            // Push the name to submitted names
            this.submittedNames.push(this.name)
            console.log(this.submittedNames)
            // Hide the modal manually
            this.$nextTick(() => {
                this.$bvModal.hide('modal-prevent-closing')
                this.editModal = false
            })
        },
    }
}
</script>