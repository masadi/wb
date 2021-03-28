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
                <template v-slot:cell(komponen)="row">
                    {{row.item.indikator.atribut.aspek.komponen.nama}}
                </template>
                <template v-slot:cell(rumusan_pertanyaan)="row">
                    {{row.item.pertanyaan}}
                </template>
                <template v-slot:cell(actions)="row">
                    <b-dropdown id="dropdown-dropleft" dropleft text="Aksi" variant="success" class="m-2">
                        <b-dropdown-item href="javascript:" @click="openShowModal(row)"><i class="fas fa-search"></i> Detil</b-dropdown-item>
                        <b-dropdown-item href="javascript:" @click="editData(row)"><i class="fas fa-edit"></i> Edit</b-dropdown-item>
                        <b-dropdown-item href="javascript:" @click="deleteData(row.item.instrumen_id)"><i class="fas fa-trash"></i> Hapus</b-dropdown-item>
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
        <b-modal id="modal-xl" size="xl" v-model="showModal" title="Detil Instrumen">
            <table class="table table-border">
                <tr>
                    <td>Komponen</td>
                    <td>: {{ (modalText.indikator) ? (modalText.indikator.atribut) ? (modalText.indikator.atribut.aspek) ? modalText.indikator.atribut.aspek.komponen.nama : '-' : '-' : '-' }}</td>
                </tr>
                <tr>
                    <td>Aspek</td>
                    <td>: {{ (modalText.indikator) ? (modalText.indikator.atribut) ? modalText.indikator.atribut.aspek.nama : '-' : '-' }}</td>
                </tr>
                <tr>
                    <td>Atribut</td>
                    <td>: {{ (modalText.indikator) ? (modalText.indikator.atribut) ? modalText.indikator.atribut.nama : '-' : '-' }}</td>
                </tr>
                <tr>
                    <td>Indikator</td>
                    <td>: {{ (modalText.indikator) ? modalText.indikator.nama : '-' }}</td>
                </tr>
                <tr>
                    <td>Rumusan Pertanyaan</td>
                    <td>: {{ modalText.pertanyaan }}</td>
                </tr>
                <!--span v-for="(list, index) in row.item.kategori">
                    <span>{{list.nama}}</span><span v-if="index+1 < row.item.kategori.length">, </span>
                </span-->
                <tr v-for="(list, index) in modalText.subs">
                    <td>Capaian Kinerja {{list.urut}}</td>
                    <td>: {{ list.pertanyaan }}</td>
                </tr>
            </table>
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
        <div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="modalEdit" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Perbaharui Instrumen</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form @submit.prevent="updateData()">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nama Aspek</label>
                            <input v-model="form.id" type="hidden" name="id"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('id') }">
                            <input v-model="form.pertanyaan" type="text" name="pertanyaan"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('pertanyaan') }">
                            <has-error :form="form" field="pertanyaan"></has-error>
                        </div>
                        <div class="form-group">
                            <label>Petunjuk Pengisian</label>
                            <textarea rows="10" cols="5" class="form-control" v-model="form.petunjuk_pengisian"></textarea>
                            <has-error :form="form" field="petunjuk_pengisian"></has-error>
                        </div>
                            <template v-for="sub in data_subs">
                                <div class="form-group">
                                    <label>Jawaban Instrumen {{sub.urut}}</label>
                                    <input v-model="form.pertanyaan_sub[sub.instrumen_id]" type="text" class="form-control" :class="{ 'is-invalid': form.errors.has('pertanyaan_sub') }">
                                    <has-error :form="form" field="pertanyaan_sub"></has-error>
                                </div>
                            </template>
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
            data_subs: [],
            editmode: false,
            form: new Form({
                id : '',
                pertanyaan: '',
                petunjuk_pengisian: '',
                pertanyaan_sub: {},
            }),
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
        openShowModal(row) {
            console.log(row.item.title);
            this.showModal = true
            this.modalText = row.item
            this.selected = row.item
        },
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
        deleteData(id){
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Tindakan ini tidak dapat dikembalikan!",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.value) {
                    return fetch('/api/instrumen/'+id, {
                        method: 'DELETE',
                    }).then(()=>{
                    //this.form.delete('api/komponen/'+id).then(()=>{
                        Swal.fire(
                            'Berhasil!',
                            'Data Instrumen berhasil dihapus',
                            'success'
                        ).then(()=>{
                            this.loadPerPage(10);
                        });
                    }).catch((data)=> {
                        Swal.fire("Failed!", data.message, "warning");
                    });
                }
            })
        },
        editData(row) {
            this.editmode = true
            this.editModal = true
            this.form.id = row.item.instrumen_id
            this.form.pertanyaan = row.item.pertanyaan
            this.form.petunjuk_pengisian = row.item.petunjuk_pengisian
            this.data_subs = row.item.subs
            var tempSubID = {};
            var SoalSub = {}
            $.each(row.item.subs, function(key, value) {
                tempSubID[value.instrumen_id] = value.instrumen_id
                SoalSub[value.instrumen_id] = value.pertanyaan
            });
            console.log(SoalSub);
            this.form.pertanyaan_sub = SoalSub
            //this.form.urut = row.item.urut
            $('#modalEdit').modal('show');
        },
        updateData(){
            let id = this.form.id;
            this.form.put('/api/instrumen/'+id).then((response)=>{
                $('#modalEdit').modal('hide');
                Toast.fire({
                    icon: 'success',
                    title: response.message
                });
                this.loadPerPage(10);
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