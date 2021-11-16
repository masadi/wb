<template>
<div class="no">
    <div class="content-header">
        <div class="container-fluid">
            <h1 class="m-0 text-dark">Profil Pengguna</h1>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
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
                })
        },
    },
}
</script>
