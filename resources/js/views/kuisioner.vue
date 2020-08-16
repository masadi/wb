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
                        <div class="row">
                            <div class="row">
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
                                    <!-- timeline item -->
                                    <div>
                                        <i class="fas fa-envelope bg-blue"></i>
                                        <div class="timeline-item">
                                            <span class="time"><i class="fas fa-clock"></i> 12:05</span>
                                            <h3 class="timeline-header"><a href="#">Support Team</a> sent you an email</h3>

                                            <div class="timeline-body">
                                                Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles,
                                                weebly ning heekya handango imeem plugg dopplr jibjab, movity
                                                jajah plickers sifteo edmodo ifttt zimbra. Babblely odeo kaboodle
                                                quora plaxo ideeli hulu weebly balihoo...
                                            </div>
                                            <div class="timeline-footer">
                                                <a class="btn btn-primary btn-sm">Read more</a>
                                                <a class="btn btn-danger btn-sm">Delete</a>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- END timeline item -->
                                    <!-- timeline item -->
                                    <div>
                                        <i class="fas fa-user bg-green"></i>
                                        <div class="timeline-item">
                                            <span class="time"><i class="fas fa-clock"></i> 5 mins ago</span>
                                            <h3 class="timeline-header no-border"><a href="#">Sarah Young</a> accepted your friend request</h3>
                                        </div>
                                    </div>
                                    <!-- END timeline item -->
                                    <!-- timeline item -->
                                    <div>
                                        <i class="fas fa-comments bg-yellow"></i>
                                        <div class="timeline-item">
                                            <span class="time"><i class="fas fa-clock"></i> 27 mins ago</span>
                                            <h3 class="timeline-header"><a href="#">Jay White</a> commented on your post</h3>
                                            <div class="timeline-body">
                                                Take me to your leader!
                                                Switzerland is small and neutral!
                                                We are more like Germany, ambitious and misunderstood!
                                            </div>
                                            <div class="timeline-footer">
                                                <a class="btn btn-warning btn-sm">View comment</a>
                                            </div>
                                        </div>
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
        axios.get('/api/kuisioner').then(({data}) => this.kuisioners = data);
    },
    data() {
        return {
            kuisioners: [],
        }
    },
    methods: {
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