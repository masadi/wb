<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Data Hasil Validasi</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><router-link tag="a" to="/beranda">Beranda</router-link></li>
                            <li class="breadcrumb-item active">Data Hasil Validasi</li>
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
                                    Data Hasil Validasi
                                </h3>
                                <div class="card-tools">
                                    <button class="btn btn-success btn-sm btn-block btn-flat" v-on:click="newModal">Proses Validasi</button>
                                </div>
                            </div>
                            <div class="card-body">
                                <app-datatable :items="items" :fields="fields" :meta="meta" :title="'Hapus Sekolah'" @per_page="handlePerPage" @pagination="handlePagination" @search="handleSearch" @sort="handleSort"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <div class="modal fade" id="modalAdd" tabindex="-1" role="dialog" aria-labelledby="modalAdd" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Proses Validasi</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="insertData()" method="post" class="form-horizontal">
                        <div class="modal-body">
                            <div class="form-group row">
                                <label for="verifikator_id" class="col-sm-2 col-form-label">Tim Penjamin Mutu</label>
                                <div class="col-sm-10">
                                    <v-select :options="verifikator" id="verifikator_id" label="text" index="value" @input="getSekolahSasaran" v-model="form.verifikator_id" placeholder="== Pilih Tim Penjamin Mutu == "></v-select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="aspek_id" class="col-sm-2 col-form-label">Sekolah</label>
                                <div class="col-sm-10">
                                    <v-select :options="sekolah" label="text" index="value" @input="getRapor" v-model="form.sekolah_sasaran_id" placeholder="== Pilih Sekolah == "></v-select>
                                </div>
                            </div>
                            <input type="hidden" v-model="form.rapor_mutu_id">
                            <div v-show="table_rapor_mutu">
                            <h4 class="text-center">Kelengkapan Laporan Tim Penjamin Mutu</h4>
                            <table class="table">
                                <tr>
                                    <th class="text-center">Jenis Dokumen</th>
                                    <th class="text-center">Isian</th>
                                    <th class="text-center">Unduh Dokumen</th>
                                    <th class="text-center">Konfirmasi</th>
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
                                    <td class="text-center">
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" id="term_berita" v-model='form.berita_acara'>
                                            <label for="term_berita" class="custom-control-label">Lengkap</label>
                                        </div>
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
                                    <td class="text-center">
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" id="term_laporan" v-model='form.laporan'>
                                            <label for="term_laporan" class="custom-control-label">Lengkap</label>
                                        </div>
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
                                    <td class="text-center">
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" id="term_instrumen_sekolah" v-model='form.instrumen_sekolah'>
                                            <label for="term_instrumen_sekolah" class="custom-control-label">Lengkap</label>
                                        </div>
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
                                    <td class="text-center">
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" id="term_instrumen_penjamin_mutu" v-model='form.instrumen_penjamin_mutu'>
                                            <label for="term_instrumen_penjamin_mutu" class="custom-control-label">Lengkap</label>
                                        </div>
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
                                    <td class="text-center">
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" id="term_instrumen_koreksi" v-model='form.instrumen_koreksi'>
                                            <label for="term_instrumen_koreksi" class="custom-control-label">Lengkap</label>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <my-loader/>
    </div>
</template>
<script>
    import Datatable from './../components/Validasi.vue' //IMPORT COMPONENT DATATABLENYA
    import axios from 'axios' //IMPORT AXIOS
export default {
    //KETIKA COMPONENT INI DILOAD
    created() {
        //MAKA AKAN MENJALANKAN FUNGSI BERIKUT
        this.loadPostsData()
    },
    data() {
        return {
            user: user,
            fields: [
                {key: 'sekolah_sasaran_id', 'label': 'Nama Sekolah', sortable: true},
                {key: 'verifikator_id', 'label': 'Nama Penjamin Mutu', sortable: true},
                {key: 'nilai_sekolah', 'label': 'Nilai Sekolah', sortable: true},
                {key: 'nilai_verifikator', 'label': 'Nilai Verifikasi', sortable: true},
                {key: 'status_rapor_id', 'label': 'Status', sortable: true},
                {key: 'actions', 'label': 'Aksi', sortable: false}, //TAMBAHKAN CODE INI
            ],
            items: [], //DEFAULT VALUE DARI ITEMS ADALAH KOSONG
            meta: [], //JUGA BERLAKU UNTUK META
            current_page: 1, //DEFAULT PAGE YANG AKTIF ADA PAGE 1
            per_page: 10, //DEFAULT LOAD PERPAGE ADALAH 10
            search: '',
            sortBy: 'created_at', //DEFAULT SORTNYA ADALAH CREATED_AT
            sortByDesc: true, //ASCEDING
            form: new Form({
                user_direktorat_id: user.user_id,
                verifikator_id: '',
                sekolah_sasaran_id : '',
                berita_acara: false,
                instrumen_sekolah : false,
                instrumen_penjamin_mutu: false,
                instrumen_koreksi : false,
                laporan: false,
                rapor_mutu_id : '',
            }),
            verifikator: [],
            sekolah : [],
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
    components: {
        'app-datatable': Datatable //REGISTER COMPONENT DATATABLE
    },
    methods: {
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
                    sekolah_sasaran_id: this.form.sekolah_sasaran_id.value,
                    verifikator_id: this.form.verifikator_id.value,
                    rapor_mutu_id: this.form.rapor_mutu_id,
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
        getSekolahSasaran(e){
            if(!e){
                this.form.sekolah_sasaran_id = ''
                return false
            }
            axios.post(`/api/validasi/get-data`, {
                verifikator_id: e.value
            })
            .then((response) => {
                let getData = response.data
                this.sekolah = getData.data.result
                this.form.sekolah_sasaran_id = ''
            })
        },
        getRapor(e){
            if(!e){
                return false
            }
            axios.post(`/api/validasi/get-data`, {
                verifikator_id: this.form.verifikator_id.value,
                sekolah_sasaran_id: e.value,
                user_id: e.user_id
            })
            .then((response) => {
                let getData = response.data
                this.table_rapor_mutu = 1
                this.rapor_mutu = getData.data
                this.form.rapor_mutu_id = getData.data.rapor_mutu_id
            })
        },
        newModal(){
            //axios.get(`/api/validasi/get-verifikator`)
            axios.post(`/api/validasi/get-data`)
            .then((response) => {
                let getData = response.data
                this.verifikator = getData.data.result
                this.form.verifikator_id = ''
                this.form.sekolah_sasaran_id = ''
                this.sekolah = []
                this.form.berita_acara = false
                this.form.instrumen_sekolah = false
                this.form.instrumen_penjamin_mutu = false
                this.form.instrumen_koreksi = false
                this.form.laporan = false
                this.form.rapor_mutu_id = ''
                $('#modalAdd').modal('show');
            });
        },
        loadPostsData() {
            let current_page = this.search == '' ? this.current_page:1
            //LAKUKAN REQUEST KE API UNTUK MENGAMBIL DATA POSTINGAN
            axios.get(`/api/validasi`, {
                //KIRIMKAN PARAMETER BERUPA PAGE YANG SEDANG DILOAD, PENCARIAN, LOAD PERPAGE DAN SORTING.
                params: {
                    data: 'validasi',
                    user_id: user.user_id,
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
        insertData(){
            this.form.post('/api/validasi/post-data').then((response)=>{
                console.log(response);
                $('#modalAdd').modal('hide');
                Toast.fire({
                    icon: response.icon,
                    title: response.message
                });
                this.loadPostsData();
            }).catch((e)=>{
                var errors = [];
                $.each(e, function(k, v) {
                    errors.push(v[0]);
                })
                console.log(errors);
                Swal.fire({
                    title : 'Validasi Gagal',
                    html: errors.join('<br>'),
                    icon: 'error',
                }).then((result) => {
                    /*
                    this.table_rapor_mutu = ''
                    this.rapor_mutu.sekolah = ''
                    this.rapor_mutu.penjamin_mutu = ''
                    */
                });
            })
        },
    }
}
</script>
