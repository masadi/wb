<template>
  <section class="content">
    <div class="container-fluid">
        <div class="row">

          <div class="col-12">
        
            <div class="card" v-if="$gate.isAdmin()">
              <div class="card-header">
                <h3 class="card-title">Data Trader Baru</h3>

                <div class="card-tools">
                  
                </div>
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
                      <th>Nilai Rebate</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                     <tr v-for="trader in data_trader.data" :key="trader.id">

                      <td>{{trader.nama_lengkap}}</td>
                      <td class="text-capitalize">{{trader.nomor_akun}}</td>
                      <td>{{trader.email}}</td>
                      <td>{{trader.telepon}}</td>
                      <td>{{trader.bank}}</td>
                      <td>{{trader.nomor_rekening}}</td>
                      <td>{{trader.nilai_rebate}}</td>
                      <td>

                        <a href="#" @click="editModal(trader)">
                            <i class="fa fa-edit blue"></i>
                        </a>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                  <pagination :data="data_trader" @pagination-change-page="getResults"></pagination>
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
                    <h5 class="modal-title" v-show="!editmode">Tambah Data Trader</h5>
                    <h5 class="modal-title" v-show="editmode">Edit Data Trader</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- <form @submit.prevent="createUser"> -->

                <form @submit.prevent="editmode ? updateTrader() : createTrader()">
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
                                class="form-control" :class="{ 'is-invalid': form.errors.has('bank') }" required>
                            <has-error :form="form" field="bank"></has-error>
                        </div>
                        <div class="form-group">
                            <label>Nomor Rekening</label>
                            <input v-model="form.nomor_rekening" type="text" name="nomor_rekening"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('nomor_rekening') }" required>
                            <has-error :form="form" field="nomor_rekening"></has-error>
                        </div>
                        <div class="form-group">
                            <label>Nilai Rebate</label>
                            <input v-model="form.nilai_rebate" type="text" name="nilai_rebate"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('nilai_rebate') }" required>
                            <has-error :form="form" field="nilai_rebate"></has-error>
                        </div>
                        <div class="form-group">
                            <label>Status SUB IB</label>
                            <!--select name="sub_id_id" v-model="form.sub_id_id" id="sub_id_id" class="form-control" :class="{ 'is-invalid': form.errors.has('sub_id_id') }">
                                <option value="">Pilih Sub ID</option>
                                <option value="admin">Admin</option>
                                <option value="user">Standard User</option>
                            </select>
                            <has-error :form="form" field="sub_id_id"></has-error-->
                            <v-select :options="[{label: 'Ya', code: 'ya'}, {label: 'Tidak', code: 'tidak'}]" v-model="form.sub_ib" :class="{ 'is-invalid': form.errors.has('sub_ib') }" required></v-select>
                            <has-error :form="form" field="sub_ib"></has-error>
                        </div>
                        <div class="form-group">
                            <label>Upline SUB IB</label>
                            <!--select name="sub_id_id" v-model="form.sub_id_id" id="sub_id_id" class="form-control" :class="{ 'is-invalid': form.errors.has('sub_id_id') }">
                                <option value="">Pilih Sub ID</option>
                                <option value="admin">Admin</option>
                                <option value="user">Standard User</option>
                            </select>
                            <has-error :form="form" field="sub_id_id"></has-error-->
                            <v-select :options="data_sub_ib" v-model="form.sub_ib_id" :class="{ 'is-invalid': form.errors.has('sub_ib_id') }"></v-select>
                            <has-error :form="form" field="sub_ib_id"></has-error>
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
                data_trader : {},
                data_sub_ib: [],
                form: new Form({
                    id : '',
                    nama_lengkap: '',
                    nomor_akun: '',
                    email: '',
                    telepon: '',
                    bank: '',
                    nomor_rekening: '',
                    nilai_rebate: '',
                    sub_ib: '',
                    sub_ib_id: '',
                })
            }
        },
        methods: {

            getResults(page = 1) {

                  this.$Progress.start();
                  
                  axios.get('/api/master/trader?page=' + page).then(({ data }) => (this.data_trader = data.data));

                  this.$Progress.finish();
            },
            updateTrader(){
                this.$Progress.start();
                this.form.put('/api/trader/'+this.form.id)
                .then((response) => {
                    // success
                    $('#addNew').modal('hide');
                    Toast.fire({
                      icon: 'success',
                      title: response.data.message
                    });
                    this.$Progress.finish();
                        //  Fire.$emit('AfterCreate');

                    this.loaddata_trader();
                })
                .catch(() => {
                    this.$Progress.fail();
                });

            },
            editModal(trader){
                this.editmode = true;
                this.loaddata_sub_ib();
                this.form.reset();
                $('#addNew').modal('show');
                //this.form.fill(trader);
                this.form.id = trader.id
                this.form.nama_lengkap = trader.nama_lengkap
                this.form.nomor_akun = trader.nomor_akun
                this.form.email = trader.email
                this.form.telepon = trader.telepon
                this.form.bank = trader.bank
                this.form.nomor_rekening = trader.nomor_rekening
                this.form.nilai_rebate = trader.nilai_rebate
                //this.form.sub_ib = trader.sub_id
                //this.form.sub_ib_id = trader.sub_ib_id
                //this.$emit('input', trader.sub_id);
                console.log(trader)
            },
            newModal(){
                this.loaddata_sub_ib();
                this.editmode = false;
                this.form.reset();
                $('#addNew').modal('show');
            },

            loaddata_trader(page = 1){
                if(this.$gate.isAdmin()){
                    axios.get('/api/master/trader?page=' + page).then(({ data }) => (this.data_trader = data.data));
                }
            },
            loaddata_sub_ib(){
                //axios.get("/api/get-sub-ib").then(({ data }) => (this.form.data_sub_ib = data.data));
                axios.get(`/api/get-sub-ib`).then((response) => {
                    var tempData = [];
                    let getData = response.data
                    getData.data.forEach(myFunction)
                    //var a = { value: {sekolah_id: null, npsn: null}, text: 'Pilih Sekolah' }
                    function myFunction(item) {
                    var a = {};
                    //a.value = {code: item.id, label:item.nama_lengkap}
                    //a.value = {sekolah_id: item.sekolah_id}
                    //a.text = item.nama_lengkap
                    a.code = item.id
                    a.label = item.nama_lengkap
                    tempData.push(a)
                    }
                    this.data_sub_ib = tempData
                    console.log(this.data_sub_ib);
                })
            },
            createTrader(){

                this.form.post('/api/trader')
                .then((response)=>{
                    $('#addNew').modal('hide');

                    Toast.fire({
                            icon: 'success',
                            title: response.data.message
                    });

                    this.$Progress.finish();
                    this.loaddata_trader();
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
            this.loaddata_trader();
            this.$Progress.finish();
        }
    }
</script>
