<template>
    <div>
        <div class="content-header">
            <div class="card bg-secondary">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-9">
                            <h2>Perangkat Kuesioner</h2>
                            <h1>Pemetaan Penjaminan Mutu Pendidikan</h1>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box bg-info">
                            <div class="info-box-content">
                                <span class="info-box-text">Progres Pengisian Anda</span>
                                <span class="info-box-number">70%</span>

                                <div class="progress">
                                <div class="progress-bar" style="width: 70%"></div>
                                </div>
                                <span class="progress-description">
                                    <button class="btn btn-success btn-sm btn-block btn-flat" v-on:click="hitung_nilai_kuisioner">Hitung Nilai Kuisioner</button>
                                </span>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                                    <!--div class="card">
                                        <div class="card-body">
                                            <table class="table table-hover">
                                                <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>User</th>
                                                    <th>a</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr v-for="kuisioner in kuisioners">
                                                    <td>{{kuisioner.id}}</td>
                                                    <td>{{kuisioner.nama}}</td>
                                                    <td>a</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div-->
                                    <div class="timeline">
                                        <div v-for="kuisioner in kuisioners">
                                            <i class="fas fa-check bg-blue"></i>
                                            <div class="timeline-item">
                                                <div class="timeline-body">
                                                    <h2>Komponen {{kuisioner.nama}}</h2>
                                                    <div class="progress" style="height: 30px;">
                                                        <div class="progress-bar bg-success" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">25%</div>
                                                    </div>
                                                </div>
                                                <div class="timeline-footer">
                                                    <a class="btn btn-primary btn-flat" v-on:click="detilKuisioner(kuisioner.id)">Detil Pengisian Kuesioner</a>
                                                    <a class="btn btn-danger btn-flat" v-on:click="prosesKuisioner(kuisioner.id)">Proses Pengisian Kuesioner</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    // VueJS components will run here.
    //import Instrumen from './components/Kuisioner.vue' //IMPORT COMPONENT DATATABLENYA
import axios from 'axios' //IMPORT AXIOS

export default {
    //props: ['user'],
    //KETIKA COMPONENT INI DILOAD
    created() {
        //MAKA AKAN MENJALANKAN FUNGSI BERIKUT
        //this.loadPostsData()
        //axios.get('/api/kuisioner').then(({data}) => this.kuisioners = data);
        axios.post(`/api/kuisioner`, {
            user_id: user.user_id,
        }).then((response) => {
            this.kuisioners = response.data
        });
    },
    data() {
        return {
            kuisioners: [],
        }
    },
    methods: {
        detilKuisioner: function (id) {
            this.$router.push({ name: 'detil_pengisian', params: { id: id } })
        },
        prosesKuisioner(id){
            this.$router.push({ name: 'proses_pengisian', params: { id: id } })
            //this.$router.push({ path: `/proses-pengisian-kuisioner/${id}` })
        },
        hitung_nilai_kuisioner: function (event) {
      // `this` inside methods points to the Vue instance
            Swal.fire({
                title: 'Apakah Anda Yakin?',
                text: "Proses ini akan sedikit memakan waktu!",
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya!',
                cancelButtonText: 'Batal',
                showLoaderOnConfirm: true,
            }).then((result) => {
                if (result.value) {
                    axios.get(`/api/hitung-nilai-instrumen/${user.user_id}`).then(() => {
                        Swal.fire(
                            'Selesai',
                            'Hitung Nilai Instrumen Berhasil!',
                            'success'
                        ).then(() => {
                            this.loadPostsData();
                        })
                    }).catch((data)=> {
                        Swal.fire("Gagal!", data.message, "warning");
                    });
                }
            })
        },
        //METHOD INI AKAN MENGHANDLE REQUEST DATA KE API
    }
}
</script>