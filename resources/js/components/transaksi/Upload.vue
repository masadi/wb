<template>
  <section class="content">
    <div class="container-fluid">
        <div class="row">

          <div class="col-12">
        
            <div class="card" v-if="$gate.isAdmin()">
              <div class="card-header">
                <h3 class="card-title">Upload File Rebate</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="form-group">
                            <label>Upload File Rebate</label>
                            <input type="file" name="file" @change="fileUpload($event.target)"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('file') }">
                            <has-error :form="form" field="file"></has-error>
                        </div>
                        <div class="progress">
                        <!-- PROGRESS BAR DENGAN VALUE NYA KITA DAPATKAN DARI VARIABLE progressBar -->
                        <div class="progress-bar" role="progressbar" 
                            :style="{width: progressBar + '%'}" 
                            :aria-valuenow="progressBar" 
                            aria-valuemin="0" 
                            aria-valuemax="100"></div>
                    </div>
              </div>
              <!-- /.card-body -->
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
                progressBar: 0,
                editmode: false,
                data_sub_ib : {},
                form: new Form({
                    file : null,
                })
            }
        },
        methods: {
            fileUpload(event) {
                this.file = event.files[0]
                this.isLoading = true
                let formData = new FormData();
                formData.append('file', this.file);
                axios.post('/api/transaksi/upload-file', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    },
                    //FUNGSI INI YANG MEMILIKI PERAN UNTUK MENGUBAH SEBERAPA JAUH PROGRESS UPLOAD FILE BERJALAN
                    onUploadProgress: function( progressEvent ) {
                        //DATA TERSEBUT AKAN DI ASSIGN KE VARIABLE progressBar
                        this.progressBar = parseInt(Math.round((progressEvent.loaded * 100) / progressEvent.total))
                    }.bind(this)
                }).then((response) => {
                    setTimeout(() => {
                        this.isLoading = false
                        this.progressBar = 0
                    });
                    Toast.fire({
                            icon: 'success',
                            title: 'Upload berhasil'
                    });
                })
            },
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

                    //this.loadDollar();
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

                this.form.post('/api/transaksi/upload-file')
                .then((response)=>{
                    $('#addNew').modal('hide');

                    Toast.fire({
                            icon: 'success',
                            title: response.data.message
                    });

                    this.$Progress.finish();
                    //this.loadDollar();
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
            //this.loadDollar();
            this.$Progress.finish();
        }
    }
</script>
