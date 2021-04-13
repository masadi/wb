<template>
  <section class="content">
    <div class="container-fluid">
        <div class="row">

          <div class="col-12">
        
            <div class="card" v-if="$gate.isAdmin()">
              <div class="card-header">
                <h3 class="card-title">Data Rebate Trader</h3>

                <!--div class="card-tools">
                  
                  <button type="button" class="btn btn-sm btn-primary" @click="newModal">
                      <i class="fa fa-plus-square"></i>
                      Tambah Data
                  </button>
                </div-->
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <thead>
                    <tr>
                        <th>Tanggal Upload</th>
                      <th>Nama Lengkap</th>
                      <th>Nomor Akun</th>
                      <th>Bank</th>
                      <th>Rekening</th>
                      <th>Volume Trading</th>
                      <th>Kadar Per Lot</th>
                      <th>Jumlah Rebate</th>
                      <th>Detil</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                     <tr v-for="sub_ib in data_sub_ib.data" :key="sub_ib.id">
                         <td>{{ sub_ib.tanggal_upload | date }}</td>
                      <td>{{sub_ib.trader.nama_lengkap}}</td>
                      <td class="text-capitalize">{{sub_ib.trader.nomor_akun}}</td>
                      <td>{{sub_ib.trader.bank}}</td>
                      <td>{{sub_ib.trader.nomor_rekening}}</td>
                      <td>{{sub_ib.paidsum}}</td>
                      <td>{{sub_ib.trader.nilai_rebate}}</td>
                      <td>{{sub_ib.rebatea}}</td>
                      <td>

                        <a href="#" @click="editModal(sub_ib)">
                            <i class="fa fa-eye blue"></i>
                        </a>
                      </td>
                      <td>
                        <!--
                            if transfer = merah (selesai)
                            if belum = hijau (transfer)
                            -->
                        <a href="#" class="btn btn-danger btn-sm">
                            Selesai
                        </a>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                  <pagination :data="data_sub_ib" @pagination-change-page="getResults"></pagination>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>


        <div v-if="!$gate.isAdmin()">
            <not-found></not-found>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNew" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" v-show="!editmode">Tambah Data SUB IB</h5>
                    <h5 class="modal-title" v-show="editmode">Detil Rebate Trader</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- <form @submit.prevent="createUser"> -->

                <form @submit.prevent="editmode ? updateSubIb() : createSubIb()">
                    <div class="modal-body">
                        <p>Sedang dalam  pengembangan</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                  </form>
                </div>
            </div>
        </div>
    </div>
  </section>
</template>

<script>
    export default {
        data () {
            return {
                editmode: false,
                data_sub_ib : {},
                form: new Form({
                    id : '',
                    nama_lengkap: '',
                    nomor_akun: '',
                    email: '',
                    telepon: '',
                    bank: '',
                    nomor_rekening: '',
                })
            }
        },
        methods: {

            getResults(page = 1) {

                  this.$Progress.start();
                  
                  axios.get('/api/transaksi/rebate?page=' + page).then(({ data }) => (this.data_sub_ib = data.data));

                  this.$Progress.finish();
            },
            updateSubIb(){
                this.$Progress.start();
                this.form.put('/api/sub-ib/'+this.form.id)
                .then((response) => {
                    // success
                    $('#addNew').modal('hide');
                    Toast.fire({
                      icon: 'success',
                      title: response.data.message
                    });
                    this.$Progress.finish();
                        //  Fire.$emit('AfterCreate');

                    this.loadSubIbs();
                })
                .catch(() => {
                    this.$Progress.fail();
                });

            },
            editModal(category){
                this.editmode = true;
                this.form.reset();
                $('#addNew').modal('show');
                this.form.fill(category);
            },
            newModal(){
                this.editmode = false;
                this.form.reset();
                $('#addNew').modal('show');
            },

            loadSubIbs(page = 1){
                if(this.$gate.isAdmin()){
                    axios.get('/api/transaksi/rebate?page=' + page).then(({ data }) => (this.data_sub_ib = data.data));
                }
            },
            
            createSubIb(){

                this.form.post('/api/sub-ib')
                .then((response)=>{
                    $('#addNew').modal('hide');

                    Toast.fire({
                            icon: 'success',
                            title: response.data.message
                    });

                    this.$Progress.finish();
                    this.loadSubIbs();
                })
                .catch(()=>{
                    Toast.fire({
                        icon: 'error',
                        title: 'Some error occured! Please try again'
                    });
                })
            }

        },
        mounted() {
            console.log('Component mounted.')
        },
        created() {

            this.$Progress.start();
            this.loadSubIbs();
            this.$Progress.finish();
        }
    }
</script>
