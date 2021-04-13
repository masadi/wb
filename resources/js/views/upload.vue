<template>
  <div class="no">
    <div class="content-header">
      <div class="container-fluid">
        <h1 class="m-0 text-dark">Beranda</h1>
      </div>
    </div>
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-body">
                <label>Upload File Rebate</label>
                <input
                  type="file"
                  name="file"
                  @change="fileUpload($event.target)"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('file') }"
                />
                <has-error :form="form" field="file"></has-error>
                <div class="progress">
                  <div
                    class="progress-bar"
                    role="progressbar"
                    :style="{ width: progressBar + '%' }"
                    :aria-valuenow="progressBar"
                    aria-valuemin="0"
                    aria-valuemax="100"
                  ></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <my-loader />
  </div>
</template>

<script>
import Chart from "chart.js";
import axios from "axios"; //IMPORT AXIOS
export default {
  //KETIKA COMPONENT INI DILOAD
  data() {
    return {
      user: user.id,
      progressBar: 0,
      form: new Form({
        file: null,
      }),
    };
  },
  //mounted() {
  //this.createChart('kemajuan', this.planetChartData);
  //},
  methods: {
    fileUpload(event) {
      this.file = event.files[0];
      this.isLoading = true;
      let formData = new FormData();
      formData.append("file", this.file);
      axios
        .post("/api/master/simpan-file", formData, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
          //FUNGSI INI YANG MEMILIKI PERAN UNTUK MENGUBAH SEBERAPA JAUH PROGRESS UPLOAD FILE BERJALAN
          onUploadProgress: function (progressEvent) {
            //DATA TERSEBUT AKAN DI ASSIGN KE VARIABLE progressBar
            this.progressBar = parseInt(
              Math.round((progressEvent.loaded * 100) / progressEvent.total)
            );
          }.bind(this),
        })
        .then((response) => {
          setTimeout(() => {
            this.isLoading = false;
            this.progressBar = 0;
          });
          Toast.fire({
            icon: "success",
            title: "Upload berhasil",
          });
        });
    },
    getResults(page = 1) {
      this.$Progress.start();

      //axios.get('/api/dollar?page=' + page).then(({ data }) => (this.form.dollar = data.data));

      this.$Progress.finish();
    },
  },
};
</script>
