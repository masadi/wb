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
          <input type="text" class="form-control" @input="search" />
        </div>
      </div>
    </div>
    <!-- BLOCK INI AKAN MENGHASILKAN LIST DATA DALAM BENTUK TABLE MENGGUNAKAN COMPONENT TABLE DARI BOOTSTRAP VUE -->

    <!-- :ITEMS ADALAH DATA YANG AKAN DITAMPILKAN -->
    <!-- :FIELDS AKAN MENJADI HEADER DARI TABLE, MAKA BERISI FIELD YANG SALING BERKORELASI DENGAN ITEMS -->
    <!-- :sort-by.sync & :sort-desc.sync AKAN MENGHANDLE FITUR SORTING -->
    <b-table striped hover :items="items" :fields="fields" :sort-by.sync="sortBy" :sort-desc.sync="sortDesc" show-empty>
      <template v-slot:cell(actions)="row">
        <b-dropdown id="dropdown-dropleft" dropleft text="Aksi" size="sm" variant="success" v-show="hasRole('admin') || hasRole('direktorat')">
          <b-dropdown-item href="javascript:" @click="listSekolah(row.item.user_id)"><i class="fas fa-folder"></i> List Sekolah Sasaran</b-dropdown-item>
          <b-dropdown-item href="javascript:" @click="addSekolah(row.item.user_id)"><i class="fas fa-folder-plus"></i> Tambah Sekolah Sasaran</b-dropdown-item>
          <b-dropdown-item href="javascript:" @click="editData(row)"><i class="fas fa-edit"></i> Edit</b-dropdown-item>
          <b-dropdown-item href="javascript:" @click="deleteData(row.item.user_id)"><i class="fas fa-trash"></i> Hapus</b-dropdown-item>
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
    <b-modal id="modal-lg" size="lg" v-model="showModal" title="Detil Penjamin Mutu">
      <table class="table">
        <tr>
          <td width="20%">Nama</td>
          <td width="80%">: {{ modalText.name }}</td>
        </tr>
        <tr>
          <td>NIP</td>
          <td>: {{ modalText.nip }}</td>
        </tr>
        <tr>
          <td>NUPTK</td>
          <td>: {{ modalText.nuptk }}</td>
        </tr>
        <tr>
          <td>Asal Institusi</td>
          <td>: {{ modalText.asal_institusi }}</td>
        </tr>
        <tr>
          <td>Alamat Institusi</td>
          <td>: {{ modalText.alamat_institusi }}</td>
        </tr>
        <tr>
          <td>Email</td>
          <td>: {{ modalText.email }}</td>
        </tr>
        <tr>
          <td>No. Handphone</td>
          <td>: {{ modalText.nomor_hp }}</td>
        </tr>
      </table>
      <template v-slot:modal-footer>
        <div class="w-100 float-right">
          <b-button variant="secondary" size="sm" @click="showModal = false"> Tutup </b-button>
        </div>
      </template>
    </b-modal>
    <b-modal id="modalEdit" size="lg" v-model="editModal" title="Edit Data Penjamin Mutu">
      <input type="hidden" v-model="form.id" />
      <div class="form-group">
        <label>Nama Lengkap</label>
        <input v-model="form.name" type="text" name="name" class="form-control" :class="{ 'is-invalid': form.errors.has('name') }" />
        <has-error :form="form" field="name"></has-error>
      </div>
      <div class="form-group">
        <label>Email</label>
        <input v-model="form.email" type="email" name="email" class="form-control" :class="{ 'is-invalid': form.errors.has('email') }" />
        <has-error :form="form" field="email"></has-error>
      </div>
      <div class="form-group">
        <label>Nomor HP</label>
        <input v-model="form.nomor_hp" type="text" name="nomor_hp" class="form-control" :class="{ 'is-invalid': form.errors.has('nomor_hp') }" />
        <has-error :form="form" field="nomor_hp"></has-error>
      </div>
      <div class="form-group">
        <label>Token</label>
        <input v-model="form.token" type="text" name="token" class="form-control" :class="{ 'is-invalid': form.errors.has('token') }" />
        <has-error :form="form" field="token"></has-error>
      </div>
      <template v-slot:modal-footer>
        <div class="w-100 float-right">
          <b-button variant="secondary" size="sm" @click="editModal = false"> Tutup </b-button>
          <b-button @click="updateData" size="sm" variant="primary">Simpan</b-button>
        </div>
      </template>
    </b-modal>
    <TambahSekolahSasaran ref="TambahSekolahSasaran"></TambahSekolahSasaran>
    <ListSekolahSasaran ref="ListSekolahSasaran"></ListSekolahSasaran>
  </div>
</template>

<script>
import _ from "lodash"; //IMPORT LODASH, DIMANA AKAN DIGUNAKAN UNTUK MEMBUAT DELAY KETIKA KOLOM PENCARIAN DIISI
import TambahSekolahSasaran from "./TambahSekolahSasaran";
import ListSekolahSasaran from "./ListSekolahSasaran";
export default {
  //PROPS INI ADALAH DATA YANG AKAN DIMINTA DARI PENGGUNA COMPONENT DATATABLE YANG KITA BUAT
  props: {
    //ITEMS STRUKTURNYA ADALAH ARRAY, KARENA BAGIAN INI BERISI DATA YANG AKAN DITAMPILKAN DAN SIFATNYA WAJIB DIKIRIMKAN KETIKA COMPONENT INI DIGUNAKAN
    items: {
      type: Array,
      required: true,
    },
    //FIELDS JUGA SAMA DENGAN ITEMS
    fields: {
      type: Array,
      required: true,
    },
    //ADAPUN META, TYPENYA ADALAH OBJECT YANG BERISI INFORMASI MENGENAL CURRENT PAGE, LOAD PERPAGE, TOTAL DATA, DAN LAIN SEBAGAINYA.
    meta: {
      required: true,
    },
    title: {
      type: String,
      default: "Delete Modal",
    },
    editUrl: {
      type: String,
      default: null,
    },
  },
  components: {
    TambahSekolahSasaran,
    ListSekolahSasaran,
  },
  data() {
    return {
      formattedMoney: null,
      editmode: false,
      form: new Form({
        id: "",
        name: "",
        email: "",
        nomor_hp: "",
        token: "",
      }),
      //VARIABLE INI AKAN MENGHADLE SORTING DATA
      sortBy: null, //FIELD YANG AKAN DISORT AKAN OTOMATIS DISIMPAN DISINI
      sortDesc: false, //SEDANGKAN JENISNYA ASCENDING ATAU DESC AKAN DISIMPAN DISINI
      //TAMBAHKAN DUA VARIABLE INI UNTUK MENGHANDLE MODAL DAN DATA YANG AKAN DIHAPUS
      deleteModal: false,
      showModal: false,
      editModal: false,
      modalText: "",
      selected: null,
    };
  },
  watch: {
    //KETIKA VALUE DARI VARIABLE sortBy BERUBAH
    sortBy(val) {
      //MAKA KITA EMIT DENGAN NAMA SORT DAN DATANYA ADALAH OBJECT BERUPA VALUE DARI SORTBY DAN SORTDESC
      //EMIT BERARTI MENGIRIMKAN DATA KEPADA PARENT ATAU YANG MEMANGGIL COMPONENT INI
      //SEHINGGA DARI PARENT TERSEBUT, KITA BISA MENGGUNAKAN VALUE YANG DIKIRIMKAN
      this.$emit("sort", {
        sortBy: this.sortBy,
        sortDesc: this.sortDesc,
      });
    },
    //KETIKA VALUE DARI SORTDESC BERUBAH
    sortDesc(val) {
      //MAKA CARA YANG SAMA AKAN DIKERJAKAN
      this.$emit("sort", {
        sortBy: this.sortBy,
        sortDesc: this.sortDesc,
      });
    },
  },
  methods: {
    //JIKA SELECT BOX DIGANTI, MAKA FUNGSI INI AKAN DIJALANKAN
    addSekolah(id) {
      this.$refs.TambahSekolahSasaran.show(id);
    },
    listSekolah(id) {
      this.$refs.ListSekolahSasaran.show(id);
    },
    loadPerPage(val) {
      //DAN KITA EMIT LAGI DENGAN NAMA per_page DAN VALUE SESUAI PER_PAGE YANG DIPILIH
      this.$emit("per_page", this.meta.per_page);
    },
    //KETIKA PAGINATION BERUBAH, MAKA FUNGSI INI AKAN DIJALANKAN
    changePage(val) {
      //KIRIM EMIT DENGAN NAMA PAGINATION DAN VALUENYA ADALAH HALAMAN YANG DIPILIH OLEH USER
      this.$emit("pagination", val);
    },
    //KETIKA KOTAK PENCARIAN DIISI, MAKA FUNGSI INI AKAN DIJALANKAN
    //KITA GUNAKAN DEBOUNCE UNTUK MEMBUAT DELAY, DIMANA FUNGSI INI AKAN DIJALANKAN
    //500 MIL SECOND SETELAH USER BERHENTI MENGETIK
    search: _.debounce(function (e) {
      //KIRIM EMIT DENGAN NAMA SEARCH DAN VALUE SESUAI YANG DIKETIKKAN OLEH USER
      this.$emit("search", e.target.value);
    }, 500),
    deleteData(id) {
      Swal.fire({
        title: "Apakah Anda yakin?",
        text: "Tindakan ini tidak dapat dikembalikan!",
        icon: "info",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Ya!",
        cancelButtonText: "Batal",
      }).then((result) => {
        if (result.value) {
          return (
            fetch("/api/users/" + id, {
              method: "DELETE",
            })
              /*.then((response) => {
                            console.log(response)
                            return false;
                            //this.form.delete('api/komponen/'+id).then(()=>{
                            Swal.fire(
                                'Berhasil!',
                                'Data Pengguna berhasil dihapus',
                                'success'
                            ).then(() => {
                                this.loadPerPage(10);
                            });
                        }).catch((data) => {
                            Swal.fire("Failed!", data.message, "warning");
                        });*/
              .then((response) => response.json())
              .then((data) => {
                //console.log(data)
                Swal.fire(data.title, data.text, data.icon).then(() => {
                  if (data.icon !== "error") {
                    this.loadPerPage(10);
                  }
                });
              }) // Manipulate the data retrieved back, if we want to do something with it
              .catch((err) => console.log(err))
          ); // Do something with the error
        }
      });
    },
    openShowModal(row) {
      this.showModal = true;
      this.modalText = row.item;
    },
    editData(row) {
      this.editModal = true;
      this.form.id = row.item.user_id;
      this.form.name = row.item.name;
      this.form.email = row.item.email;
      this.form.nomor_hp = row.item.nomor_hp;
      this.form.token = row.item.token;
    },
    updateData() {
      let id = this.form.id;
      this.form
        .put("/api/users/" + id)
        .then((response) => {
          this.editModal = false;
          Toast.fire({
            icon: "success",
            title: response.message,
          });
          this.loadPerPage(10);
        })
        .catch((e) => {
          Toast.fire({
            icon: "error",
            title: "Some error occured! Please try again",
          });
        });
    },
    insetData() {
      this.form
        .post("/api/users/store")
        .then((response) => {
          this.addModal = false;
          Toast.fire({
            icon: "success",
            title: response.message,
          });
          this.loadPerPage(10);
        })
        .catch((e) => {
          Toast.fire({
            icon: "error",
            title: "Some error occured! Please try again",
          });
        });
    },
  },
};
</script>
