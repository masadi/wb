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
        <template v-slot:cell(jml_sekolah)="row">
            {{row.item.sekolah_sasaran_count}}
        </template>
        <template v-slot:cell(actions)="row">
            <b-dropdown id="dropdown-dropleft" dropleft text="Aksi" size="sm" variant="success" v-show="hasRole('admin') || hasRole('direktorat')">
                <b-dropdown-item href="javascript:" @click="listSekolah(row.item.pendamping_id)"><i class="fas fa-folder"></i> List Sekolah Binaan</b-dropdown-item>
                <b-dropdown-item href="javascript:" @click="addSekolah(row.item.pendamping_id)"><i class="fas fa-folder-plus"></i> Tambah Sekolah Binaan</b-dropdown-item>
                <b-dropdown-item href="javascript:" @click="editData(row)"><i class="fas fa-edit"></i> Edit</b-dropdown-item>
                <b-dropdown-item href="javascript:" @click="deleteData(row.item.pendamping_id)"><i class="fas fa-trash"></i> Hapus</b-dropdown-item>
            </b-dropdown>
            <button v-show="!hasRole('admin') && !hasRole('direktorat')" class="btn btn-warning btn-sm" @click="openShowModal(row)">Detil</button>
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
    <b-modal id="modal-lg" size="lg" v-model="showModal" title="Detil Pendamping">
        <table class="table">
            <tr>
                <td width="20%">Nama</td>
                <td width="80%">: {{modalText.nama}}</td>
            </tr>
            <tr>
                <td>NIP</td>
                <td>: {{modalText.nip}}</td>
            </tr>
            <tr>
                <td>NUPTK</td>
                <td>: {{modalText.nuptk}}</td>
            </tr>
            <tr>
                <td>Asal Instansi</td>
                <td>: {{modalText.instansi}}</td>
            </tr>
            <tr>
                <td>Email</td>
                <td>: {{modalText.email}}</td>
            </tr>
            <tr>
                <td>No. Handphone</td>
                <td>: {{modalText.nomor_hp}}</td>
            </tr>
        </table>
        <template v-slot:modal-footer>
            <div class="w-100 float-right">
                <b-button variant="secondary" size="sm" @click="showModal=false">
                    Tutup
                </b-button>
            </div>
        </template>
    </b-modal>
    <b-modal ref="editModal" size="lg" title="Edit Data Pendamping">
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
        <template v-slot:modal-footer>
            <div class="w-100 float-right">
                <b-button variant="secondary" size="sm" @click="hideModal">
                    Tutup
                </b-button>
                <b-button variant="success" size="sm" @click="updateData">
                    Perbaharui
                </b-button>
            </div>
        </template>
        <!--form @submit.prevent="updateData()" method="post">
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button v-show="editmode" type="submit" class="btn btn-success">Perbaharui</button>
                <button v-show="!editmode" type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form-->
    </b-modal>
    <TambahSekolahPendamping ref="TambahSekolahPendamping"></TambahSekolahPendamping>
    <ListSekolahPendamping ref="ListSekolahPendamping"></ListSekolahPendamping>
</div>
</template>

<script>
import _ from 'lodash' //IMPORT LODASH, DIMANA AKAN DIGUNAKAN UNTUK MEMBUAT DELAY KETIKA KOLOM PENCARIAN DIISI
import TambahSekolahPendamping from "./TambahSekolahPendamping";
import ListSekolahPendamping from "./ListSekolahPendamping";
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
    components: {
        TambahSekolahPendamping,
        ListSekolahPendamping
    },
    data() {
        return {
            formattedMoney: null,
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
            //VARIABLE INI AKAN MENGHADLE SORTING DATA
            sortBy: null, //FIELD YANG AKAN DISORT AKAN OTOMATIS DISIMPAN DISINI
            sortDesc: false, //SEDANGKAN JENISNYA ASCENDING ATAU DESC AKAN DISIMPAN DISINI
            //TAMBAHKAN DUA VARIABLE INI UNTUK MENGHANDLE MODAL DAN DATA YANG AKAN DIHAPUS
            deleteModal: false,
            showModal: false,
            editModal: false,
            modalText: '',
            selected: null,
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
        addSekolah(id) {
            this.$refs.TambahSekolahPendamping.show(id);
        },
        listSekolah(id) {
            this.$refs.ListSekolahPendamping.show(id);
        },
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
                    return fetch('/api/referensi/delete-pendamping/' + id, {
                        method: 'DELETE',
                    }).then(() => {
                        //this.form.delete('api/komponen/'+id).then(()=>{
                        Swal.fire(
                            'Berhasil!',
                            'Data Pendamping berhasil dihapus',
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
            console.log(row);
            this.form.id = row.item.pendamping_id
            this.form.nama = row.item.nama
            this.form.nip = row.item.nip
            this.form.nuptk = row.item.nuptk
            this.form.instansi = row.item.instansi
            this.form.email = row.item.email
            this.form.nomor_hp = row.item.nomor_hp
            this.form.token = row.item.token
            /*this.editmode = true
            this.editModal = true

            $('#modalEdit').modal('show');*/
            this.$refs['editModal'].show()
        },
        hideModal() {
            this.$refs['editModal'].hide()
        },
        updateData() {
            let id = this.form.id
            this.form.put('/api/referensi/update-pendamping/' + id).then((response) => {
                this.$refs['editModal'].hide()
                Toast.fire({
                    icon: response.status,
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
    }
}
</script>
