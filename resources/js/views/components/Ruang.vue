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
                    <h5 class="modal-title">Perbaharui Indikator Kinerja</h5>
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
                        <input v-model="form.bangunan_id" type="hidden" name="ruang_id" class="form-control" :class="{ 'is-invalid': form.errors.has('ruang_id') }">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label class="col-sm-6 col-form-label">Kerusakan Batu Bata/Partisi (%)</label>
                                    <div class="col-sm-6">
                                        <input v-model="form.rusak_bata_dinding" type="text" name="rusak_bata_dinding"
                                            class="form-control" :class="{ 'is-invalid': form.errors.has('rusak_bata_dinding') }">
                                        <has-error :form="form" field="rusak_bata_dinding"></has-error>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label class="col-sm-6 col-form-label">Keterangan Batu Bata/Partisi</label>
                                    <div class="col-sm-6">
                                        <input v-model="form.ket_bata_dinding" type="text" name="ket_bata_dinding"
                                            class="form-control" :class="{ 'is-invalid': form.errors.has('ket_bata_dinding') }">
                                        <has-error :form="form" field="ket_bata_dinding"></has-error>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label class="col-sm-6 col-form-label">Kerusakan Kaca (%)</label>
                                    <div class="col-sm-6">
                                        <input v-model="form.rusak_daun_jendela" type="text" name="rusak_daun_jendela"
                                            class="form-control" :class="{ 'is-invalid': form.errors.has('rusak_daun_jendela') }">
                                        <has-error :form="form" field="rusak_daun_jendela"></has-error>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label class="col-sm-6 col-form-label">Keterangan Kaca</label>
                                    <div class="col-sm-6">
                                        <input v-model="form.ket_daun_jendela" type="text" name="ket_daun_jendela"
                                            class="form-control" :class="{ 'is-invalid': form.errors.has('ket_daun_jendela') }">
                                        <has-error :form="form" field="ket_daun_jendela"></has-error>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label class="col-sm-6 col-form-label">Kerusakan Pintu (%)</label>
                                    <div class="col-sm-6">
                                        <input v-model="form.rusak_daun_pintu" type="text" name="rusak_daun_pintu"
                                            class="form-control" :class="{ 'is-invalid': form.errors.has('rusak_daun_pintu') }">
                                        <has-error :form="form" field="rusak_daun_pintu"></has-error>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label class="col-sm-6 col-form-label">Keterangan Pintu</label>
                                    <div class="col-sm-6">
                                        <input v-model="form.ket_daun_pintu" type="text" name="ket_daun_pintu"
                                            class="form-control" :class="{ 'is-invalid': form.errors.has('ket_daun_pintu') }">
                                        <has-error :form="form" field="ket_daun_pintu"></has-error>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label class="col-sm-6 col-form-label">Kerusakan Kusen (%)</label>
                                    <div class="col-sm-6">
                                        <input v-model="form.rusak_kusen" type="text" name="rusak_kusen"
                                            class="form-control" :class="{ 'is-invalid': form.errors.has('rusak_kusen') }">
                                        <has-error :form="form" field="rusak_kusen"></has-error>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label class="col-sm-6 col-form-label">Keterangan Kusen</label>
                                    <div class="col-sm-6">
                                        <input v-model="form.ket_kusen" type="text" name="ket_kusen"
                                            class="form-control" :class="{ 'is-invalid': form.errors.has('ket_kusen') }">
                                        <has-error :form="form" field="ket_kusen"></has-error>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label class="col-sm-6 col-form-label">Kerusakan Plafon (%)</label>
                                    <div class="col-sm-6">
                                        <input v-model="form.rusak_tutup_plafon" type="text" name="rusak_tutup_plafon"
                                            class="form-control" :class="{ 'is-invalid': form.errors.has('rusak_tutup_plafon') }">
                                        <has-error :form="form" field="rusak_tutup_plafon"></has-error>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label class="col-sm-6 col-form-label">Keterangan Plafon</label>
                                    <div class="col-sm-6">
                                        <input v-model="form.ket_tutup_plafon" type="text" name="ket_tutup_plafon"
                                            class="form-control" :class="{ 'is-invalid': form.errors.has('ket_tutup_plafon') }">
                                        <has-error :form="form" field="ket_tutup_plafon"></has-error>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label class="col-sm-6 col-form-label">Kerusakan Penutup Lantai (%)</label>
                                    <div class="col-sm-6">
                                        <input v-model="form.rusak_tutup_lantai" type="text" name="rusak_tutup_lantai"
                                            class="form-control" :class="{ 'is-invalid': form.errors.has('rusak_tutup_lantai') }">
                                        <has-error :form="form" field="rusak_tutup_lantai"></has-error>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label class="col-sm-6 col-form-label">Keterangan Penutup Lantai</label>
                                    <div class="col-sm-6">
                                        <input v-model="form.ket_penutup_lantai" type="text" name="ket_penutup_lantai"
                                            class="form-control" :class="{ 'is-invalid': form.errors.has('ket_penutup_lantai') }">
                                        <has-error :form="form" field="ket_penutup_lantai"></has-error>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Klasifikasi Kerusakan Instalasi Listrik</label>
                                    <div class="col-sm-9">
                                        <v-select :options="data_listrik" v-model="form.ket_inst_listrik" @input="updateListrik" />
                                        <has-error :form="form" field="ket_inst_listrik"></has-error>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Kerusakan Instalasi Listrik (%)</label>
                                    <div class="col-sm-9">
                                        <input v-model="form.rusak_inst_listrik" type="text" name="rusak_inst_listrik" readonly
                                            class="form-control" :class="{ 'is-invalid': form.errors.has('rusak_inst_listrik') }">
                                        <has-error :form="form" field="rusak_inst_listrik"></has-error>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Klasifikasi Kerusakan Instalasi Air</label>
                                    <div class="col-sm-9">
                                        <v-select :options="data_air" v-model="form.ket_inst_air" @input="updateAir" />
                                        <has-error :form="form" field="ket_inst_air"></has-error>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Kerusakan Instalasi Air (%)</label>
                                    <div class="col-sm-9">
                                        <input v-model="form.rusak_inst_air" type="text" name="rusak_inst_air" readonly
                                            class="form-control" :class="{ 'is-invalid': form.errors.has('rusak_inst_air') }">
                                        <has-error :form="form" field="rusak_inst_air"></has-error>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label class="col-sm-6 col-form-label">Kerusakan Drainase Limbah (%)</label>
                                    <div class="col-sm-6">
                                        <input v-model="form.rusak_drainase" type="text" name="rusak_drainase"
                                            class="form-control" :class="{ 'is-invalid': form.errors.has('rusak_drainase') }">
                                        <has-error :form="form" field="rusak_drainase"></has-error>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label class="col-sm-6 col-form-label">Keterangan Drainase Limbah</label>
                                    <div class="col-sm-6">
                                        <input v-model="form.ket_drainase" type="text" name="ket_drainase"
                                            class="form-control" :class="{ 'is-invalid': form.errors.has('ket_drainase') }">
                                        <has-error :form="form" field="ket_drainase"></has-error>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label class="col-sm-6 col-form-label">Kerusakan Finishing Langit-langit (%)</label>
                                    <div class="col-sm-6">
                                        <input v-model="form.rusak_finish_plafon" type="text" name="rusak_finish_plafon"
                                            class="form-control" :class="{ 'is-invalid': form.errors.has('rusak_finish_plafon') }">
                                        <has-error :form="form" field="rusak_finish_plafon"></has-error>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label class="col-sm-6 col-form-label">Keterangan Finishing Langit-langit</label>
                                    <div class="col-sm-6">
                                        <input v-model="form.ket_finish_plafon" type="text" name="ket_finish_plafon"
                                            class="form-control" :class="{ 'is-invalid': form.errors.has('ket_finish_plafon') }">
                                        <has-error :form="form" field="ket_finish_plafon"></has-error>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label class="col-sm-6 col-form-label">Kerusakan Finishing Dinding (%)</label>
                                    <div class="col-sm-6">
                                        <input v-model="form.rusak_finish_dinding" type="text" name="rusak_finish_dinding"
                                            class="form-control" :class="{ 'is-invalid': form.errors.has('rusak_finish_dinding') }">
                                        <has-error :form="form" field="rusak_finish_dinding"></has-error>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label class="col-sm-6 col-form-label">Keterangan Finishing Dinding</label>
                                    <div class="col-sm-6">
                                        <input v-model="form.ket_finish_dinding" type="text" name="ket_finish_dinding"
                                            class="form-control" :class="{ 'is-invalid': form.errors.has('ket_finish_dinding') }">
                                        <has-error :form="form" field="ket_finish_dinding"></has-error>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label class="col-sm-6 col-form-label">Kerusakan Finishing Kusen/Pintu (%)</label>
                                    <div class="col-sm-6">
                                        <input v-model="form.rusak_finish_kpj" type="text" name="rusak_finish_kpj"
                                            class="form-control" :class="{ 'is-invalid': form.errors.has('rusak_finish_kpj') }">
                                        <has-error :form="form" field="rusak_finish_kpj"></has-error>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label class="col-sm-6 col-form-label">Keterangan Finishing Kusen/Pintu</label>
                                    <div class="col-sm-6">
                                        <input v-model="form.ket_finish_kpj" type="text" name="ket_finish_kpj"
                                            class="form-control" :class="{ 'is-invalid': form.errors.has('ket_finish_kpj') }">
                                        <has-error :form="form" field="ket_finish_kpj"></has-error>
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
            editmode: false,
            //VARIABLE INI AKAN MENGHADLE SORTING DATA
            sortBy: null, //FIELD YANG AKAN DISORT AKAN OTOMATIS DISIMPAN DISINI
            sortDesc: false, //SEDANGKAN JENISNYA ASCENDING ATAU DESC AKAN DISIMPAN DISINI
            //TAMBAHKAN DUA VARIABLE INI UNTUK MENGHANDLE MODAL DAN DATA YANG AKAN DIHAPUS
            deleteModal: false,
            showModal: false,
            editModal: false,
            modalText: '',
            selected: null,
            form: new Form({
                rusak_bata_dinding: 0,
                ket_bata_dinding: '-',
                ruang_id: '',
                rusak_bata_dinding: 0,
                ket_bata_dinding: '-',
                rusak_daun_jendela: 0,
                ket_daun_jendela: '-',
                rusak_daun_pintu: 0,
                ket_daun_pintu: '-',
                rusak_kusen: 0,
                ket_kusen: '-',
                rusak_tutup_plafon: 0,
                ket_tutup_plafon: '-',
                rusak_tutup_lantai: 0,
                ket_penutup_lantai: '-',
                ket_inst_listrik: 'Tidak ada kerusakan',
                rusak_inst_listrik: 0,
                ket_inst_air: 'Tidak ada kerusakan',
                rusak_inst_air: 0,
                rusak_drainase: 0,
                ket_drainase: '-',
                rusak_finish_plafon: 0,
                ket_finish_plafon: '-',
                rusak_finish_dinding: 0,
                ket_finish_dinding	: '-',
                rusak_finish_kpj: 0,
                ket_finish_kpj: '-',
            }),
            data_listrik: [
                {label: 'Tidak ada kerusakan', code: 0},
                {label: 'Sebagian kecil komponen dari panel-panel LP rusak, ada sedkit jalur kabel instalasi shortage, sebagian kecil armature rusak ringan, sehingga biaya perbaikan kurang dari 5% dari biaya instalasi baru', code: 1},
                {label: 'Beberapa komponen dari panel-panel LP rusak, sebagian kecil jalur kabel instalasi shortage, sehingga armature rusak ringan, sehingga  biaya perbaikan 5-20% dari biaya instalasi baru', code: 2},
                {label: 'Beberapa komponen dari panel-panel LP rusak, sebagian kecil jalur kabel instalasi shortage, sehingga armature rusak ringan hingga berat, sehingga  biaya perbaikan 20-50% dari biaya instalasi baru', code: 3},
                {label: 'Sebagian besar komponen panel-panel LP rusak, sebagian besar kabel instalasi shortage, sebagian besar armature rusak berat, sehingga biaya perbaikan lebih  dari 50 % dari instalasi baru', code: 4},
                {label: 'Sebagian besar komponen panel-panel LP rusak, sebagian besar kabel instalasi shortage, seluruh armature rusak berat, sehingga biaya perbaikan lebih dari 50 % dari instalasi baru', code: 5},
            ],
            data_air: [
                {label: 'Tidak ada kerusakan', code: 0},
                {label: 'Kebocoran pipa terbats ditempat yang terlihat atau mudah dicapai, keran-keran kecil rusak, sehingga biaya perbaikan kurang dari 1 % biaya instalasi baru', code: '0.3'},
                {label: 'Bagian-bagian kecil pemipaan bocor, motor pompa terbakar, keran-keran kecil rusak, sehingga biaya perbaikan antara 1-10% dari biaya instalasi baru', code: '0.6'},
                {label: 'Pompa, motor, pipa, dan keran rusak apabuila diganti atau diperbaiki memerlukan biaya antara 10-25 % dari biaya instalasi baru', code: '0.9'},
                {label: 'Sebagian besar pompa, sebagian besar motor terbakar, pipa utama bocor namun ditempat terbuka, beberapa keran tidak befungsi, sehingga biaya perbaikan 25- 50 % dari biaya instalasi baru', code: '1.2'},
                {label: 'Pompa –pompa rusak total, motor terbakar, dibanyak tempat terbuka dan tutup pipa-pipa bocor, keran-keran tidak berfungsi, sehingga perbaikan instalasi perlu menyeluruh, dengan perkiraan biaya lebih dari 50% dari biaya instalasi baru', code: '1.5'},
            ],
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
        updateListrik(val){
            this.form.rusak_inst_listrik = val.code
        },
        updateAir(val){
            this.form.rusak_inst_air = val.code
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
                    return fetch('/api/indikator/'+id, {
                        method: 'DELETE',
                    }).then(()=>{
                    //this.form.delete('api/komponen/'+id).then(()=>{
                        Swal.fire(
                            'Berhasil!',
                            'Data Indikator Kinerja berhasil dihapus',
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
            this.form.ruang_id = row.item.ruang_id
            axios.get(`/api/kondisi/ruang`, {
                //KIRIMKAN PARAMETER BERUPA PAGE YANG SEDANG DILOAD, PENCARIAN, LOAD PERPAGE DAN SORTING.
                params: {
                    ruang_id: row.item.ruang_id,
                }
            })
            .then((response) => {
                //JIKA RESPONSENYA DITERIMA
                let getData = response.data.data
                if(getData){
                    /*this.form.bangunan_id = getData.bangunan_id
                    this.form.rusak_pondasi = number_format(getData.rusak_pondasi)
                    this.form.ket_pondasi = getData.ket_pondasi*/
                    this.form.ruang_id = getData.ruang_id
                    this.form.rusak_bata_dinding = number_format(getData.rusak_bata_dinding)
                    this.form.ket_bata_dinding = getData.ket_bata_dinding
                    this.form.rusak_daun_jendela = number_format(getData.rusak_daun_jendela)
                    this.form.ket_daun_jendela = getData.ket_daun_jendela
                    this.form.rusak_daun_pintu = number_format(getData.rusak_daun_pintu)
                    this.form.ket_daun_pintu = getData.ket_daun_pintu
                    this.form.rusak_kusen = number_format(getData.rusak_kusen)
                    this.form.ket_kusen = getData.ket_kusen
                    this.form.rusak_tutup_plafon = number_format(getData.rusak_tutup_plafon)
                    this.form.ket_tutup_plafon = getData.ket_tutup_plafon
                    this.form.rusak_tutup_lantai = number_format(getData.rusak_tutup_lantai)
                    this.form.ket_penutup_lantai = getData.ket_penutup_lantai
                    this.form.rusak_inst_listrik = getData.rusak_inst_listrik
                    this.form.ket_inst_listrik = getData.ket_inst_listrik
                    this.form.rusak_inst_air = getData.rusak_inst_air
                    this.form.ket_inst_air = getData.ket_inst_air
                    this.form.rusak_drainase = number_format(getData.rusak_drainase)
                    this.form.ket_drainase = getData.ket_drainase
                    this.form.rusak_finish_plafon = number_format(getData.rusak_finish_plafon)
                    this.form.ket_finish_plafon = getData.ket_finish_plafon
                    this.form.rusak_finish_dinding = number_format(getData.rusak_finish_dinding)
                    this.form.ket_finish_dinding = getData.ket_finish_dinding	
                    this.form.rusak_finish_kpj = number_format(getData.rusak_finish_kpj)
                    this.form.ket_finish_kpj = getData.ket_finish_kpj
                    let total_kerusakan = 0
                    //Number(getData.rusak_pondasi) + Number(getData.rusak_bata_dinding) + Number(getData.rusak_daun_jendela) + Number(getData.rusak_daun_pintu) + Number(getData.rusak_kusen)
                    this.presentase_kerusakan = number_format(total_kerusakan,2)
                    let make_kriteria = null
                    if(total_kerusakan == 0){
                        make_kriteria = 'BAIK'
                    } else if(total_kerusakan >= 1 && total_kerusakan <= 30){
                        make_kriteria = 'RINGAN'
                    } else if(total_kerusakan >= 31 && total_kerusakan <= 45){
                        make_kriteria = 'SEDANG'
                    } else if(total_kerusakan >= 46 && total_kerusakan <= 65){
                        make_kriteria = 'BERAT'
                    } else if(total_kerusakan > 66){
                        make_kriteria = 'TOTAL'
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
            this.form.post('/api/kondisi/simpan-ruang').then((response)=>{
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
            this.form.put('/api/indikator/'+id).then((response)=>{
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