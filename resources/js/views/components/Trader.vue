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
                <label class="mr-2">Cari</label>
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
        <template v-slot:table-busy>
            <div class="text-center text-danger my-2">
                <b-spinner class="align-middle"></b-spinner>
                <strong>Loading...</strong>
            </div>
        </template>
        <template v-slot:cell(sub_ib)="row">
            {{(row.item.trader_email.upline) ? row.item.trader_email.upline.trader.nama_lengkap : ''}}
        </template>
        <template v-slot:cell(actions)="row">
            <b-dropdown v-show="hasRole('admin')" id="dropdown-dropleft" dropleft text="Aksi" variant="success" size="sm">
                <b-dropdown-item href="javascript:" @click="openShowModal(row)"><i class="fas fa-eye"></i> Detil</b-dropdown-item>
                <b-dropdown-item href="javascript:" @click="editData(row)"><i class="fas fa-edit"></i> Edit</b-dropdown-item>
                <b-dropdown-item href="javascript:" @click="deleteData(row)"><i class="fas fa-trash"></i> Hapus</b-dropdown-item>
            </b-dropdown>
        </template>
    </b-table>

    <!-- BAGIAN INI AKAN MENAMPILKAN JUMLAH DATA YANG DI-LOAD -->
    <div class="row">
        <div class="col-md-6">
            <p>Menampilkan {{ meta.from }} sampai {{ meta.to }} dari {{ meta.total }} entri</p>
        </div>

        <!-- BLOCK INI AKAN MENJADI PAGINATION DARI DATA YANG DITAMPILKAN -->
        <div class="col-md-6">
            <!-- DAN KETIKA TERJADI PERGANTIAN PAGE, MAKA AKAN MENJALANKAN FUNGSI changePage -->
            <b-pagination v-model="meta.current_page" :total-rows="meta.total" :per-page="meta.per_page" align="right" @change="changePage" aria-controls="dw-datatable"></b-pagination>
        </div>
    </div>
    <b-modal id="modal-xl" size="lg" v-model="showModal" title="Detil Trader">
        <table class="table">
            <tr>
                <td width="30%">Nama Lengkap</td>
                <td width="70%">: {{modalText.nama_lengkap}}</td>
            </tr>
            <tr>
                <td>Nomor Akun</td>
                <td>: {{modalText.nomor_akun}}</td>
            </tr>
            <tr>
                <td>Email</td>
                <td>: {{modalText.email}}</td>
            </tr>
            <tr>
                <td>Telepon</td>
                <td>: {{modalText.telepon}}</td>
            </tr>
            <tr>
                <td>Nama Bank</td>
                <td>: {{modalText.bank}}</td>
            </tr>
            <tr>
                <td>Nomor Rekening</td>
                <td>: {{modalText.nomor_rekening}}</td>
            </tr>
            <tr>
                <td>Nilai Rebate</td>
                <td>: {{modalText.nilai_rebate}}</td>
            </tr>
            <tr>
                <td>Sebagai SUB IB</td>
                <td>: {{modalText.sub_ib}}</td>
            </tr>
            <tr>
                <td>Jumlah Downline</td>
                <td>: {{(modalText.downline) ? modalText.downline.length : 0}}</td>
            </tr>
        </table>
        <template v-if="modalText.downline_count">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Nama Lengkap</th>
                        <th class="text-center">Email</th>
                        <th class="text-center">No HP</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(downline, index) in modalText.downline">
                        <td class="text-center">{{index + 1}}</td>
                        <td>{{downline.trader.nama_lengkap}}</td>
                        <td>{{downline.trader.email}}</td>
                        <td>{{downline.trader.telepon}}</td>
                    </tr>
                </tbody>
            </table>
        </template>
        <template v-slot:modal-footer>
            <div class="w-100 float-right">
                <b-button variant="secondary" size="sm" @click="showModal=false">
                    Tutup
                </b-button>
            </div>
        </template>
    </b-modal>
    <b-modal id="modal-xl" size="lg" v-model="editModal" title="Edit Data Trader">
        <template v-slot:modal-header>
            <h5 class="modal-title">Edit Data Trader</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </template>
        <template v-slot:default="{ hide }">
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input v-model="form.id" type="hidden" name="id" class="form-control">
                <input v-model="form.nama_lengkap" type="text" name="nama_lengkap" class="form-control" :class="{ 'is-invalid': form.errors.has('nama_lengkap') }">
                <has-error :form="form" field="nama_lengkap"></has-error>
            </div>
            <div class="form-group">
                <label>Nomor Akun</label>
                <input v-model="form.nomor_akun" type="text" name="nomor_akun" class="form-control" :class="{ 'is-invalid': form.errors.has('nomor_akun') }">
                <has-error :form="form" field="nomor_akun"></has-error>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input v-model="form.email" type="email" name="email" class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
                <has-error :form="form" field="email"></has-error>
            </div>
            <div class="form-group">
                <label>Nomor HP</label>
                <input v-model="form.telepon" type="text" name="telepon" class="form-control" :class="{ 'is-invalid': form.errors.has('telepon') }">
                <has-error :form="form" field="telepon"></has-error>
            </div>
            <div class="form-group">
                <label>Nama Bank</label>
                <input v-model="form.bank" type="text" name="bank" class="form-control" :class="{ 'is-invalid': form.errors.has('bank') }">
                <has-error :form="form" field="bank"></has-error>
            </div>
            <div class="form-group">
                <label>Nomor Rekening</label>
                <input v-model="form.nomor_rekening" type="text" name="nomor_rekening" class="form-control" :class="{ 'is-invalid': form.errors.has('nomor_rekening') }">
                <has-error :form="form" field="nomor_rekening"></has-error>
            </div>
            <div class="form-group">
                <label>Nilai Rebate</label>
                <input v-model="form.nilai_rebate" type="text" name="nilai_rebate" class="form-control" :class="{ 'is-invalid': form.errors.has('nilai_rebate') }">
                <has-error :form="form" field="nilai_rebate"></has-error>
            </div>
            <div class="form-group">
                <label>Status SUB IB</label>
                <v-select label="value" :options="data_status" v-model="form.sub_ib" />
                <has-error :form="form" field="sub_ib"></has-error>
            </div>
            <div class="form-group">
                <label>Upline SUB IB</label>
                <v-select label="nama_lengkap" :options="data_upline" v-model="form.sub_ib_id" @input="showKomisi" />
                <has-error :form="form" field="sub_ib_id"></has-error>
            </div>
            <div class="form-group" v-if="form_komisi">
                <label>Komisi SUB IB</label>
                <input v-model="form.komisi_sub_id" type="text" name="komisi_sub_id" class="form-control" :class="{ 'is-invalid': form.errors.has('komisi_sub_id') }">
                <has-error :form="form" field="komisi_sub_id"></has-error>
            </div>
        </template>
        <template v-slot:modal-footer="{ hide }">
            <b-button variant="secondary" size="sm" @click="hide()">Tutup</b-button>
            <b-button variant="primary" size="sm" @click="updateData">Perbaharui</b-button>
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
        },
    },
    data() {
        return {
            form_komisi: 0,
            user: user,
            data_status: [],
            data_upline: [],
            form: new Form({
                id: '',
                nama_lengkap: '',
                nomor_akun: '',
                email: '',
                telepon: '',
                bank: '',
                nomor_rekening: '',
                nilai_rebate: '',
                sub_ib: '',
                sub_ib_id: '',
                komisi_sub_id: ''
,            }),
            //VARIABLE INI AKAN MENGHADLE SORTING DATA
            sortBy: null, //FIELD YANG AKAN DISORT AKAN OTOMATIS DISIMPAN DISINI
            sortDesc: false, //SEDANGKAN JENISNYA ASCENDING ATAU DESC AKAN DISIMPAN DISINI
            //TAMBAHKAN DUA VARIABLE INI UNTUK MENGHANDLE MODAL DAN DATA YANG AKAN DIHAPUS
            showModal: false,
            editModal: false,
            modalText: {},
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
    /*computed: {
        isDisabled: function(){
            return this.sasaran
        }
    },*/
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
        deleteData(id) {
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
                    return fetch('/api/sekolah/' + id, {
                        method: 'DELETE',
                    }).then(() => {
                        //this.form.delete('api/komponen/'+id).then(()=>{
                        Swal.fire(
                            'Berhasil!',
                            'Data Sekolah berhasil dihapus',
                            'success'
                        ).then(() => {
                            this.loadPerPage(10);
                        });
                    }).catch((data) => {
                        Swal.fire("Failed!", data.message, "warning");
                    });
                }
            })
        },
        openShowModal(row) {
            this.showModal = true
            this.modalText = row.item
        },
        editData(row) {
            let getData = row.item
            this.editmode = true
            this.editModal = true
            this.form.id = getData.id
            this.form.nama_lengkap = getData.nama_lengkap
            this.form.nomor_akun = getData.nomor_akun
            this.form.email = getData.email
            this.form.telepon = getData.telepon
            this.form.bank = getData.bank
            this.form.nomor_rekening = getData.nomor_rekening
            this.form.nilai_rebate = getData.nilai_rebate
            this.form.sub_ib = (getData.sub_ib == 'ya') ? 'Ya' : 'Tidak'
            this.form.sub_ib_id = (getData.upline) ? {id: getData.upline.id, nama_lengkap: getData.upline.trader.nama_lengkap} : ''
            if(getData.upline){
                this.form_komisi = 1
                this.form.komisi_sub_id = getData.upline.komisi
            } else {
                this.form_komisi = 0
            }
            this.getStatus()
            this.getUpline(row)
            $('#modalEdit').modal('show');
        },
        showKomisi(val){
            if(val){
                this.form_komisi = 1
            } else {
                this.form_komisi = 0
            }
        },
        updateData() {
            let id = this.form.id;
            this.form.put('/api/master/update-trader/' + id).then((response) => {
                this.editModal = false
                Toast.fire({
                    icon: 'success',
                    title: response.message
                });
                this.loadPerPage(10);
            }).catch((e) => {
                Toast.fire({
                    icon: 'error',
                    title: 'Some error occured! Please try again'
                });
            })
        },
        getStatus(){
            this.data_status = [
                {key: 'ya', value: 'Ya'},
                {key: 'tidak', value: 'Tidak'}
            ]
        },
        getUpline(row){
            axios.get(`/api/master/all-upline`, {
                params: {
                    id: row.item.id
                }
            })
            .then((response) => {
                let getData = response.data.data
                this.data_upline = getData
            })
        }
    }
}
</script>
