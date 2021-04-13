<template>
  <section class="content">
    <div class="container-fluid">
        <div class="row">

          <div class="col-12">
        
            <div class="card" v-if="$gate.isAdmin()">
              <div class="card-header">
                <h3 class="card-title">Kurs Dollar</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="form-group">
                            <label></label>
                            <input v-model="form.dollar" type="text" name="dollar"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('dollar') }">
                            <has-error :form="form" field="dollar"></has-error>
                        </div>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                  <button type="button" class="btn btn-sm btn-primary" @click="simpanData">
                      <i class="fa fa-save"></i>
                      Simpan
                  </button>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>


        <div v-if="!$gate.isAdmin()">
            <not-found></not-found>
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
                    dollar : '',
                })
            }
        },
        methods: {

            getResults(page = 1) {

                  this.$Progress.start();
                  
                  //axios.get('/api/dollar?page=' + page).then(({ data }) => (this.form.dollar = data.data));
                  

                  this.$Progress.finish();
            },
            updateSubIb(){
                this.$Progress.start();
                this.form.put('/api/dollar/'+this.form.id)
                .then((response) => {
                    // success
                    $('#addNew').modal('hide');
                    Toast.fire({
                      icon: 'success',
                      title: response.data.message
                    });
                    this.$Progress.finish();
                        //  Fire.$emit('AfterCreate');

                    this.loadDollar();
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

            loadDollar(){
                if(this.$gate.isAdmin()){
                    //axios.get("/api/dollar").then(({ data }) => (this.data_sub_ib = data.data));
                    axios.get(`/api/dollar`).then((response) => {
                        var data = response.data.data.value
                        this.form.dollar = data
                    })
                }
            },
            
            simpanData(){

                this.form.post('/api/dollar')
                .then((response)=>{
                    $('#addNew').modal('hide');

                    Toast.fire({
                            icon: 'success',
                            title: response.data.message
                    });

                    this.$Progress.finish();
                    this.loadDollar();
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
            this.loadDollar();
            this.$Progress.finish();
        }
    }
</script>
