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
                                <span class="info-box-number">{{persen_utama}}%</span>

                                <div class="progress">
                                <div class="progress-bar" v-bind:style="'width: '+persen_utama+'%;'"></div>
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
                                        <div v-for="(kuisioner, key) in kuisioners">
                                            <i class="fas fa-check bg-blue"></i>
                                            <div class="timeline-item">
                                                <div class="timeline-body">
                                                    <h2>Komponen {{kuisioner.nama}}</h2>
                                                    <div class="progress" style="height: 30px;">
                                                        <div class="progress-bar bg-success" role="progressbar" v-bind:style="'width: '+persen[key]+'%;'" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">{{persen[key]}}%</div>
                                                    </div>
                                                </div>
                                                <div class="timeline-footer">
                                                    <a class="btn btn-primary btn-flat" v-on:click="detilKuisioner(kuisioner.id)">Detil Pengisian Kuesioner</a>
                                                    <a class="btn btn-danger btn-flat" v-on:click="prosesKuisioner(kuisioner.id)">Pengisian Kuesioner</a>
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
import axios from 'axios'
export default {
    created() {
        this.loadPostsData()
    },
    data() {
        return {
            persen_utama: 0,
            persen: [],
            jumlah_kuisioner: [],
            jumlah_jawaban: [],
            kuisioners: [],
        }
    },
    methods: {
        loadPostsData(){
            axios.post(`/api/kuisioner`, {
                user_id: user.user_id,
            }).then((response) => {
                var tempData = {};
                var tempKuisioner = {};
                var tempPersen = {};
                var total_jawaban = 0;
                var total_kuisioner = 0;
                $.each(response.data, function(key, value) {
                    tempData[key] = value.jawaban_count; 
                    var i = 0;
                    $.each(value.aspek, function(a, val) {
                        i += val.instrumen_count;
                    });
                    let persen = (value.jawaban_count / i)*(100);
                        persen = persen.toFixed(0);
                    tempKuisioner[key] = i; 
                    tempPersen[key] = persen; 
                    total_jawaban += value.jawaban_count;
                    total_kuisioner += i;
                });
                let persen_total = (total_jawaban / total_kuisioner)*(100);
                    persen_total = persen_total.toFixed(0);
                this.persen_utama = persen_total;
                this.persen = tempPersen;
                this.jumlah_kuisioner = tempKuisioner;
                this.jumlah_jawaban = tempData;
                this.kuisioners = response.data
            });
        },
        detilKuisioner: function (id) {
            this.$router.push({ name: 'detil_pengisian', params: { id: id } })
        },
        prosesKuisioner(id){
            this.$router.push({ name: 'proses_pengisian', params: { id: id } })
        },
        hitung_nilai_kuisioner: function (event) {
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
    }
}
</script>