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
            <template v-slot:empty="scope">
                <div class="text-center">Tidak ada data untuk ditampilkan</div>
            </template>
            <template v-slot:cell(nama_sekolah)="row">
                {{row.item.sekolah.nama}}
            </template>
            <template v-slot:cell(nama_verifikator)="row">
                {{row.item.penjamin_mutu.name}}
            </template>
            <template v-slot:cell(nilai_sekolah)="row">
                {{row.item.sekolah.user.nilai_akhir.nilai}}
            </template>
            <template v-slot:cell(nilai_verifikator)="row">
                {{row.item.penjamin_mutu.nilai_akhir_penjamin_mutu.nilai}}
            </template>
            <template v-slot:cell(status)="row">
                {{row.item.status_rapor.nama}}
            </template>
            <template v-slot:cell(actions)="row">
                <b-dropdown id="dropdown-dropleft" dropleft text="Aksi" variant="success" size="sm">
                    <b-dropdown-item href="javascript:" @click="detilData(row)"><i class="fas fa-search-plus"></i> Detil</b-dropdown-item>
                    <b-dropdown-item href="javascript:" @click="terimaData(row)"><i class="fas fa-check"></i> Sahkan</b-dropdown-item>
                    <b-dropdown-item href="javascript:" @click="afirmasiData(row)"><i class="fas fa-sync-alt"></i> Afirmasi</b-dropdown-item>
                    <b-dropdown-item href="javascript:" @click="tolakData(row)"><i class="fas fa-ban"></i> Tolak</b-dropdown-item>
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
        <b-modal ref="detil-data" size="lg">
            <template v-slot:modal-title>
                Detil Kelengkapan Laporan Tim Penjaminan Mutu
            </template>
            <template v-slot:default="{ hide }">
                <table class="table" v-show="table_rapor_mutu">
                    <tr>
                        <th class="text-center">Jenis Dokumen</th>
                        <th class="text-center">Isian</th>
                        <th class="text-center">Unduh Dokumen</th>
                    </tr>
                    <tr>
                        <td>Berita Acara</td>
                        <td class="text-center">{{(rapor_mutu.sekolah.berita_acara) ? 'Ada' : 'Tidak Ada'}}</td>
                        <td class="text-center">
                            <b-button squared variant="success" size="sm" v-on:click="unduh('berita_acara', rapor_mutu)">
                                <b-spinner small v-show="show_spinner.berita_acara"></b-spinner>
                                <span class="sr-only" v-show="show_spinner.berita_acara">Loading...</span>
                                <i v-show="show_text.berita_acara" class="fas fa-download"></i>
                            </b-button>
                        </td>
                    </tr>
                    <tr>
                        <td>Laporan Hasil Supervisi</td>
                        <td class="text-center">{{(rapor_mutu.keterangan) ? 'Ada' : 'Tidak Ada'}}</td>
                        <td class="text-center">
                            <b-button squared variant="success" size="sm" v-on:click="unduh('laporan', rapor_mutu)">
                                <b-spinner small v-show="show_spinner.laporan"></b-spinner>
                                <span class="sr-only" v-show="show_spinner.laporan">Loading...</span>
                                <i v-show="show_text.laporan" class="fas fa-download"></i>
                            </b-button>
                        </td>
                    </tr>
                    <tr>
                        <td>Isian Instrumen Sekolah</td>
                        <td class="text-center">{{rapor_mutu.sekolah.nilai_instrumen_count}}</td>
                        <td class="text-center">
                            <b-button squared variant="success" size="sm" v-on:click="unduh('instrumen_sekolah', rapor_mutu)">
                                <b-spinner small v-show="show_spinner.instrumen_sekolah"></b-spinner>
                                <span class="sr-only" v-show="show_spinner.instrumen_sekolah">Loading...</span>
                                <i v-show="show_text.instrumen_sekolah" class="fas fa-download"></i>
                            </b-button>
                        </td>
                    </tr>
                    <tr>
                        <td>Isian Instrumen Tim Penjamin Mutu</td>
                        <td class="text-center">{{rapor_mutu.penjamin_mutu.isian_instrumen_count}}</td>
                        <td class="text-center">
                            <b-button squared variant="success" size="sm" v-on:click="unduh('instrumen_penjamin_mutu', rapor_mutu)">
                                <b-spinner small v-show="show_spinner.instrumen_penjamin_mutu"></b-spinner>
                                <span class="sr-only" v-show="show_spinner.instrumen_penjamin_mutu">Loading...</span>
                                <i v-show="show_text.instrumen_penjamin_mutu" class="fas fa-download"></i>
                            </b-button>
                        </td>
                    </tr>
                    <tr>
                        <td>Isian Instrumen Koreksi</td>
                        <td class="text-center">{{rapor_mutu.penjamin_mutu.koreksi_instrumen_count}}</td>
                        <td class="text-center">
                            <b-button squared variant="success" size="sm" v-on:click="unduh('instrumen_koreksi', rapor_mutu)">
                                <b-spinner small v-show="show_spinner.instrumen_koreksi"></b-spinner>
                                <span class="sr-only" v-show="show_spinner.instrumen_koreksi">Loading...</span>
                                <i v-show="show_text.instrumen_koreksi" class="fas fa-download"></i>
                            </b-button>
                        </td>
                    </tr>
                </table>
            </template>
            <template v-slot:modal-footer="{ ok }">
                <b-button size="sm" variant="danger" @click="ok()">
                    Tutup
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
        },
    },
    data() {
        return {
            user : user,
            //VARIABLE INI AKAN MENGHADLE SORTING DATA
            sortBy: null, //FIELD YANG AKAN DISORT AKAN OTOMATIS DISIMPAN DISINI
            sortDesc: false, //SEDANGKAN JENISNYA ASCENDING ATAU DESC AKAN DISIMPAN DISINI
            //TAMBAHKAN DUA VARIABLE INI UNTUK MENGHANDLE MODAL DAN DATA YANG AKAN DIHAPUS
            deleteModal: false,
            showModal: false,
            editModal: false,
            modalText: {},
            selected: null,
            sasaran:false,
            table_rapor_mutu: '',
            rapor_mutu: {
                sekolah : '',
                penjamin_mutu : '',
            },
            show_spinner: {
                berita_acara : false,
                laporan : false,
                instrumen_sekolah : false,
                instrumen_penjamin_mutu : false,
                instrumen_koreksi : false,
            },
            show_text: {
                berita_acara : true,
                laporan : true,
                instrumen_sekolah : true,
                instrumen_penjamin_mutu : true,
                instrumen_koreksi : true,
            }
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
        detilData(data){
            axios.post(`/api/validasi/proses`, {
                permintaan: 'detil',
                rapor_mutu_id: data.item.rapor_mutu_id,
                sekolah_sasaran_id : data.item.sekolah_sasaran_id,
                verifikator_id: data.item.verifikator_id,
                user_id: user.user_id,
            }).then((response) => {
                let getData = response.data
                this.table_rapor_mutu = 1
                this.rapor_mutu = getData.data
                this.$refs['detil-data'].show()
            });
        },
        terimaData(data){
            let nama_sekolah = data.item.sekolah.nama
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: 'Rapor Mutu Sekolah '+nama_sekolah+ ' akan disahkan!',
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.value) {
                    axios.post(`/api/validasi/proses`, {
                        permintaan: 'terima',
                        rapor_mutu_id: data.item.rapor_mutu_id,
                        sekolah_sasaran_id : data.item.sekolah_sasaran_id,
                        verifikator_id: data.item.verifikator_id,
                        user_id: user.user_id,
                    }).then((response) => {
                        Swal.fire(
                            'Berhasil!',
                            'Rapor Mutu sekolah '+nama_sekolah+' berhasil disahkan',
                            'success'
                        ).then(()=>{
                            this.loadPerPage(10);
                        });
                    });
                }
            })
        },
        afirmasiData(data){
            let nama_sekolah = data.item.sekolah.nama
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: 'Rapor Mutu Sekolah '+nama_sekolah+ ' akan dikembalikan ke akun Tim Penjaminan Mutu!',
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.value) {
                    axios.post(`/api/validasi/proses`, {
                        permintaan: 'afirmasi',
                        rapor_mutu_id: data.item.rapor_mutu_id,
                        sekolah_sasaran_id : data.item.sekolah_sasaran_id,
                        verifikator_id: data.item.verifikator_id,
                        user_id: user.user_id,
                    }).then((response) => {
                        Swal.fire(
                            'Berhasil!',
                            'Rapor Mutu sekolah '+nama_sekolah+' berhasil dikembalikan ke akun Tim Penjaminan Mutu',
                            'success'
                        ).then(()=>{
                            this.loadPerPage(10);
                        });
                    });
                }
            })
        },
        tolakData(data){
            let nama_sekolah = data.item.sekolah.nama
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: 'Rapor Mutu Sekolah '+nama_sekolah+ ' akan ditolak!',
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.value) {
                    axios.post(`/api/validasi/proses`, {
                        permintaan: 'tolak',
                        rapor_mutu_id: data.item.rapor_mutu_id,
                        sekolah_sasaran_id : data.item.sekolah_sasaran_id,
                        verifikator_id: data.item.verifikator_id,
                        user_id: user.user_id,
                    }).then((response) => {
                        Swal.fire(
                            'Berhasil!',
                            'Rapor Mutu sekolah '+nama_sekolah+' berhasil ditolak',
                            'success'
                        ).then(()=>{
                            this.loadPerPage(10);
                        });
                    });
                }
            })
        },
        unduh(val, data){
            console.log(data);
            let nama_sekolah = data.sekolah.nama
            let nama_penjamin_mutu = data.penjamin_mutu.name
            let dokumen_id = data.sekolah.berita_acara.dokumen.dokumen_id
            let nama_pdf = 'Dokumen.pdf';
            if(val == 'berita_acara'){
                this.show_spinner.berita_acara = true
                this.show_text.berita_acara = false
                nama_pdf = 'Berita Acara '+nama_sekolah+' - '+nama_penjamin_mutu+'.pdf'
            } else if(val == 'laporan'){
                this.show_spinner.laporan = true
                this.show_text.laporan = false
                nama_pdf = 'Laporan Hasil Supervisi '+nama_penjamin_mutu+' - '+nama_sekolah+'.pdf'
            } else if(val == 'instrumen_sekolah'){
                this.show_spinner.instrumen_sekolah = true
                this.show_text.instrumen_sekolah = false
                nama_pdf = 'Isian Instrumen Sekolah '+nama_sekolah+'.pdf'
            } else if(val == 'instrumen_penjamin_mutu'){
                this.show_spinner.instrumen_penjamin_mutu = true
                this.show_text.instrumen_penjamin_mutu = false
                nama_pdf = 'Isian Instrumen Tim Penjaminan Mutu '+nama_penjamin_mutu+' di Sekolah '+nama_sekolah+'.pdf'
            } else if(val == 'instrumen_koreksi'){
                this.show_spinner.instrumen_koreksi = true
                this.show_text.instrumen_koreksi = false
                nama_pdf = 'Isian Perubahan Instrumen Tim Penjaminan Mutu '+nama_penjamin_mutu+' di Sekolah '+nama_sekolah+'.pdf'
            }
            axios.get(`/api/validasi/download`, {
                params : {
                    permintaan: val,
                    dokumen_id: dokumen_id,
                    sekolah_id:data.sekolah.sekolah_id,
                    sekolah_sasaran_id: data.sekolah_sasaran_id,
                    verifikator_id: data.verifikator_id,
                    rapor_mutu_id: data.rapor_mutu_id,
                    user_id: data.sekolah.user.user_id,
                },
                responseType: 'arraybuffer'
            }).then((response) => {
                //console.log(val);
                //console.log(nama_pdf);
                //console.log(response);
                //return false;
                let blob = new Blob([response.data], { type: 'application/pdf' })
                let link = document.createElement('a')
                link.href = window.URL.createObjectURL(blob)
                link.download = nama_pdf
                link.click()
                this.show_spinner.berita_acara = false
                this.show_spinner.laporan = false
                this.show_spinner.instrumen_sekolah = false
                this.show_spinner.instrumen_penjamin_mutu = false
                this.show_spinner.instrumen_koreksi = false
                this.show_text.berita_acara = true
                this.show_text.laporan = true
                this.show_text.instrumen_sekolah = true
                this.show_text.instrumen_penjamin_mutu = true
                this.show_text.instrumen_koreksi = true
            })
        },
    }
}
</script>