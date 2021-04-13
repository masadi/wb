<template>
  <section class="content">
    <div class="container-fluid">
        <div class="row">

          <div class="col-12">
        
            <div class="card" v-if="$gate.isAdmin()">
              <div class="card-header">
                <h3 class="card-title">Data SUB IB</h3>

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
                      <th>Nama Lengkap</th>
                      <th>Nomor Akun</th>
                      <th>Email</th>
                      <th>Telepon</th>
                      <th>Bank</th>
                      <th>Rekening</th>
                      <th>Jumlah Afiliasi</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                     <tr v-for="sub_ib in data_sub_ib.data" :key="sub_ib.id">

                      <td>{{sub_ib.nama_lengkap}}</td>
                      <td class="text-capitalize">{{sub_ib.nomor_akun}}</td>
                      <td>{{sub_ib.email}}</td>
                      <td>{{sub_ib.telepon}}</td>
                      <td>{{sub_ib.bank}}</td>
                      <td>{{sub_ib.nomor_rekening}}</td>
                      <td>{{sub_ib.afiliasi_count}}</td>
                      <td>

                        <a href="#" @click="editModal(sub_ib)">
                            <i class="fa fa-edit blue"></i>
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
                    <h5 class="modal-title" v-show="editmode">Edit Data SUB IB</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- <form @submit.prevent="createUser"> -->

                <form @submit.prevent="editmode ? updateSubIb() : createSubIb()">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nama Lengkap</label>
                            <input v-model="form.nama_lengkap" type="text" name="nama_lengkap"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('nama_lengkap') }">
                            <has-error :form="form" field="nama_lengkap"></has-error>
                        </div>
                        <div class="form-group">
                            <label>Nomor Akun</label>
                            <input v-model="form.nomor_akun" type="text" name="nomor_akun"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('nomor_akun') }">
                            <has-error :form="form" field="nomor_akun"></has-error>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input v-model="form.email" type="email" name="email"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
                            <has-error :form="form" field="email"></has-error>
                        </div>
                        <div class="form-group">
                            <label>Nomor Telepon</label>
                            <input v-model="form.telepon" type="text" name="telepon"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('telepon') }">
                            <has-error :form="form" field="telepon"></has-error>
                        </div>
                        <div class="form-group">
                            <label>Bank</label>
                            <input v-model="form.bank" type="text" name="bank"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('bank') }">
                            <has-error :form="form" field="bank"></has-error>
                        </div>
                        <div class="form-group">
                            <label>Nomor Rekening</label>
                            <input v-model="form.nomor_rekening" type="text" name="nomor_rekening"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('nomor_rekening') }">
                            <has-error :form="form" field="nomor_rekening"></has-error>
                        </div>
                        <div class="form-group">
                            <label>SUB IB</label>
                            <input v-model="form.sub_ib" type="checkbox" name="sub_ib" value="ya"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('sub_ib') }">
                            <has-error :form="form" field="sub_ib"></has-error>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button v-show="editmode" type="submit" class="btn btn-success">Update</button>
                        <button v-show="!editmode" type="submit" class="btn btn-primary">Create</button>
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
                  
                  axios.get('/api/sub-ib?page=' + page).then(({ data }) => (this.data_sub_ib = data.data));

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

            loadSubIbs(){
                if(this.$gate.isAdmin()){
                    axios.get("/api/sub-ib").then(({ data }) => (this.data_sub_ib = data.data));
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
