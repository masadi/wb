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
                            <!--div class="row">
                                <div class="small-box text-center mr-2">
                                    <img src="/vendor/img/pdf.png" width="100">
                                    <div class="inner">Panduan Penggunaan Aplikasi (Sekolah)</div>
                                    <div class="small-box-footer bg-primary">
                                        <a href="/downloads/Panduan APM SMK 2020 (Sekolah).pdf" target="_blank">Unduh <i class="fas fa-download"></i></a>
                                    </div>
                                </div>
                                <div class="small-box text-center mr-2">
                                    <img src="/vendor/img/pdf.png" width="100">
                                    <div class="inner">Panduan Penggunaan Aplikasi (Penjamin Mutu)</div>
                                    <div class="small-box-footer bg-primary">
                                        <a href="/downloads/Panduan APM SMK 2020 (Penjamin Mutu).pdf" target="_blank">Unduh <i class="fas fa-download"></i></a>
                                    </div>
                                </div>
                                <div class="small-box text-center mr-2">
                                    <img src="/vendor/img/pdf.png" width="100">
                                    <div class="inner">Pedoman Penjaminan Mutu SMK</div>
                                    <div class="small-box-footer bg-primary">
                                        Unduh <i class="fas fa-download"></i>
                                    </div>
                                </div>
                            </div-->
                            <h1 class="text-center">Sedang dalam Pengembangan</h1>
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
import Chart from 'chart.js';
import axios from 'axios' //IMPORT AXIOS
export default {
    //KETIKA COMPONENT INI DILOAD
    created() {
        //MAKA AKAN MENJALANKAN FUNGSI BERIKUT
        this.loadPostsData()
    },
    data() {
        return {
            user: user.id,
        }
    },
    //mounted() {
    //this.createChart('kemajuan', this.planetChartData);
    //},
    methods: {
        loadPostsData() {
            axios.post(`/api/dashboard`, {
                    user_id: user.id,
                })
                .then((response) => {
                    let getData = response.data.data
                    if (!getData) {
                        return false
                    }
                    console.log(getData);
                })
        },
    },
}
</script>
