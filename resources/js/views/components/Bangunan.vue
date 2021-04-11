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
                <template v-slot:cell(actions)="row">
                    <b-dropdown id="dropdown-dropleft" dropleft text="Aksi" variant="success">
                        <b-dropdown-item href="javascript:" @click="inputKondisi(row)"><i class="fas fa-list"></i> Input Kondisi</b-dropdown-item>
                        <b-dropdown-item href="javascript:" @click="editData(row)"><i class="fas fa-edit"></i> Edit</b-dropdown-item>
                        <b-dropdown-item href="javascript:" @click="deleteData(row.item.id)"><i class="fas fa-trash"></i> Hapus</b-dropdown-item>
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
        <div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="modalEdit" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Perbaharui Komponen</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form @submit.prevent="updateData()">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nama Komponen</label>
                            <input v-model="form.id" type="hidden" name="id"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('id') }">
                            <input v-model="form.nama" type="text" name="nama"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('nama') }">
                            <has-error :form="form" field="nama"></has-error>
                        </div>
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
        <div class="modal fade" id="modalKondisi" tabindex="-1" role="dialog" aria-labelledby="modalKondisi" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Input Kondisi Ruang</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form @submit.prevent="updateKondisi()">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-box bg-info">
                                    <div class="info-box-content text-center">
                                        <h5 class="info-box-text">Tingkat Persentase Kerusakan</h5> 
                                        <h3 class="info-box-number">{{presentase_kerusakan}}</h3> 
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-box bg-navy">
                                    <div class="info-box-content text-center">
                                        <h5 class="info-box-text">Kriteria Kerusakan</h5> 
                                        <h3 class="info-box-number">{{kriteria_kerusakan}}</h3> 
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input v-model="form.bangunan_id" type="hidden" name="bangunan_id" class="form-control" :class="{ 'is-invalid': form.errors.has('bangunan_id') }">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Klasifikasi Kerusakan Pondasi</label>
                                    <div class="col-sm-9">
                                        <v-select :options="data_pondasi" v-model="form.ket_pondasi" @input="updatePondasi" />
                                        <has-error :form="form" field="ket_pondasi"></has-error>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Kerusakan Pondasi (%)</label>
                                    <div class="col-sm-9">
                                        <input v-model="form.rusak_pondasi" type="text" name="rusak_pondasi" readonly
                                            class="form-control" :class="{ 'is-invalid': form.errors.has('rusak_pondasi') }">
                                        <has-error :form="form" field="rusak_pondasi"></has-error>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label class="col-sm-6 col-form-label">Kerusakan Kolom (%)</label>
                                    <div class="col-sm-6">
                                        <input v-model="form.rusak_sloop_kolom_balok" type="text" name="rusak_sloop_kolom_balok"
                                            class="form-control" :class="{ 'is-invalid': form.errors.has('rusak_sloop_kolom_balok') }">
                                        <has-error :form="form" field="rusak_sloop_kolom_balok"></has-error>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label class="col-sm-6 col-form-label">Keterangan Kolom</label>
                                    <div class="col-sm-6">
                                        <input v-model="form.ket_sloop_kolom_balok" type="text" name="ket_sloop_kolom_balok"
                                            class="form-control" :class="{ 'is-invalid': form.errors.has('ket_sloop_kolom_balok') }">
                                        <has-error :form="form" field="ket_sloop_kolom_balok"></has-error>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label class="col-sm-6 col-form-label">Kerusakan Balok (%)</label>
                                    <div class="col-sm-6">
                                        <input v-model="form.rusak_kudakuda_atap" type="text" name="rusak_kudakuda_atap"
                                            class="form-control" :class="{ 'is-invalid': form.errors.has('rusak_kudakuda_atap') }">
                                        <has-error :form="form" field="rusak_kudakuda_atap"></has-error>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label class="col-sm-6 col-form-label">Keterangan Balok</label>
                                    <div class="col-sm-6">
                                        <input v-model="form.ket_kudakuda_atap" type="text" name="ket_kudakuda_atap"
                                            class="form-control" :class="{ 'is-invalid': form.errors.has('ket_kudakuda_atap') }">
                                        <has-error :form="form" field="ket_kudakuda_atap"></has-error>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label class="col-sm-6 col-form-label">Kerusakan Pelat Lantai (%)</label>
                                    <div class="col-sm-6">
                                        <input v-model="form.rusak_plester_struktur" type="text" name="rusak_plester_struktur"
                                            class="form-control" :class="{ 'is-invalid': form.errors.has('rusak_plester_struktur') }">
                                        <has-error :form="form" field="rusak_plester_struktur"></has-error>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label class="col-sm-6 col-form-label">Keterangan Pelat Lantai</label>
                                    <div class="col-sm-6">
                                        <input v-model="form.ket_plester_struktur" type="text" name="ket_plester_struktur"
                                            class="form-control" :class="{ 'is-invalid': form.errors.has('ket_plester_struktur') }">
                                        <has-error :form="form" field="ket_plester_struktur"></has-error>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label class="col-sm-6 col-form-label">Kerusakan Atap (%)</label>
                                    <div class="col-sm-6">
                                        <input v-model="form.rusak_tutup_atap" type="text" name="rusak_tutup_atap"
                                            class="form-control" :class="{ 'is-invalid': form.errors.has('rusak_tutup_atap') }">
                                        <has-error :form="form" field="rusak_tutup_atap"></has-error>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label class="col-sm-6 col-form-label">Keterangan Penutup Atap</label>
                                    <div class="col-sm-6">
                                        <b-form-group v-slot="{ ariaDescribedby }">
                                            <b-form-radio v-model="form.ket_tutup_atap" :aria-describedby="ariaDescribedby" name="ket_tutup_atap" value="BUKAN DAK">BUKAN DAK</b-form-radio>
                                            <b-form-radio v-model="form.ket_tutup_atap" :aria-describedby="ariaDescribedby" name="ket_tutup_atap" value="DAK BETON">DAK BETON</b-form-radio>
                                            <b-form-radio v-model="form.ket_tutup_atap" :aria-describedby="ariaDescribedby" name="ket_tutup_atap" value="TIDAK MEMILIKI ATAP">TIDAK MEMILIKI ATAP</b-form-radio>
                                        </b-form-group>
                                        <has-error :form="form" field="ket_tutup_atap"></has-error>
                                    </div>
                                </div>
                            </div>
                        </div>
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
            presentase_kerusakan: '0%',
            kriteria_kerusakan : '-',
            data_pondasi: [
                {label: 'Tidak ada kerusakan', code: 0},
                {label: 'Penurunan merata pada seluruh struktur bangunan', code: 20},
                {label: 'Penurunan tidak merata, namun perbedaan penurunan tidak melebihi 1/250L', code: 40},
                {label: 'Penurunan > 1/250L sehingga menimbulkan kerusakan struktur atasnya. Tanah di sekeliling bangunan naik', code: 60},
                {label: 'Bangunan miring secara kasat mata, lantai dasar naik/menggelembung', code: 80},
                {label: 'Pondasi patah, bergeser akibat longsor, stuktur atas menjadi rusak', code: 10},
            ],
            editmode: false,
            form: new Form({
                bangunan_id: '',
                rusak_pondasi : 0,
                ket_pondasi: 'Tidak ada kerusakan',
                rusak_sloop_kolom_balok: 0,
                ket_sloop_kolom_balok: '-',
                rusak_kudakuda_atap: 0,
                ket_kudakuda_atap: '-',
                rusak_plester_struktur: 0,
                ket_plester_struktur: '-',
                rusak_tutup_atap: 0,
                ket_tutup_atap: '-',
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
        updatePondasi(val){
            this.form.rusak_pondasi = val.code
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
                    return fetch('/api/hapus-bangunan/'+id, {
                        method: 'DELETE',
                    }).then(()=>{
                    //this.form.delete('api/komponen/'+id).then(()=>{
                        Swal.fire(
                            'Berhasil!',
                            'Data Bangunan berhasil dihapus',
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
            //console.log(row);
            this.editmode = true
            this.editModal = true
            $('#modalEdit').modal('show');
        },
        inputKondisi(row){
            this.editmode = true
            this.form.bangunan_id = row.item.bangunan_id
            axios.get(`/api/kondisi/bangunan`, {
                //KIRIMKAN PARAMETER BERUPA PAGE YANG SEDANG DILOAD, PENCARIAN, LOAD PERPAGE DAN SORTING.
                params: {
                    bangunan_id: row.item.bangunan_id,
                }
            })
            .then((response) => {
                //JIKA RESPONSENYA DITERIMA
                let getData = response.data.data
                if(getData){
                    this.form.bangunan_id = getData.bangunan_id
                    this.form.rusak_pondasi = number_format(getData.rusak_pondasi)
                    this.form.ket_pondasi = getData.ket_pondasi
                    this.form.rusak_sloop_kolom_balok = number_format(getData.rusak_sloop_kolom_balok)
                    this.form.ket_sloop_kolom_balok = getData.ket_sloop_kolom_balok
                    this.form.rusak_kudakuda_atap = number_format(getData.rusak_kudakuda_atap)
                    this.form.ket_kudakuda_atap = getData.ket_kudakuda_atap
                    this.form.rusak_plester_struktur = number_format(getData.rusak_plester_struktur)
                    this.form.ket_plester_struktur = getData.ket_plester_struktur
                    this.form.rusak_tutup_atap = number_format(getData.rusak_tutup_atap)
                    this.form.ket_tutup_atap = getData.ket_tutup_atap
                    let total = Number(getData.rusak_pondasi) + Number(getData.rusak_sloop_kolom_balok) + Number(getData.rusak_kudakuda_atap) + Number(getData.rusak_plester_struktur) + Number(getData.rusak_tutup_atap)
                    this.presentase_kerusakan = number_format(total,2)
                    let make_kriteria = null
                    if(total == 0){
                        make_kriteria = 'BAIK'
                    } else if(total >= 1 && total <= 20){
                        make_kriteria = 'RINGAN'
                    } else if(total > 20){
                        make_kriteria = 'SEDANG'
                    } else if(total > 50){
                        make_kriteria = 'BERAT'
                    }
                    this.kriteria_kerusakan = make_kriteria
                    console.log(getData);
                }
            })
            $('#modalKondisi').modal('show');
            function number_format(number, decimals, dec_point, thousands_sep) {
                var n = !isFinite(+number) ? 0 : +number, 
                    prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
                    sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
                    dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
                    toFixedFix = function (n, prec) {
                        // Fix for IE parseFloat(0.55).toFixed(0) = 0;
                        var k = Math.pow(10, prec);
                        return Math.round(n * k) / k;
                    },
                    s = (prec ? toFixedFix(n, prec) : Math.round(n)).toString().split('.');
                    if (s[0].length > 3) {
                        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
                    }
                    if ((s[1] || '').length < prec) {
                        s[1] = s[1] || '';
                        s[1] += new Array(prec - s[1].length + 1).join('0');
                    }
                    return s.join(dec);
                }
        },
        updateKondisi(){
            this.form.post('/api/kondisi/simpan-bangunan').then((response)=>{
                console.log(response);
                $('#modalKondisi').modal('hide');
                Toast.fire({
                    icon: 'success',
                    title: response.status
                });
                this.loadPerPage(10);
            }).catch((e)=>{
                console.log(e);
                Toast.fire({
                    icon: 'error',
                    title: 'Some error occured! Please try again'
                });
            })
        },
        updateData(){
            let id = this.form.id;
            this.form.put('/api/komponen/'+id).then((response)=>{
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