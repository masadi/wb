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
                            <div class="form-group">
                                <input v-model="form.dollar" type="text" name="dollar" class="form-control" :class="{ 'is-invalid': form.errors.has('dollar') }">
                                <has-error :form="form" field="dollar"></has-error>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="button" class="btn btn-sm btn-primary" @click="simpanData">
                                <i class="fa fa-save"></i>
                                Simpan
                            </button>
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
            form: new Form({
                dollar : '',
            })
        }
    },
    methods: {
        loadPostsData() {
            axios.get(`/api/master/dollar`, {
                    params: {
                        user_id: user.id,
                    }
                })
                .then((response) => {
                    let getData = response.data.data
                    if (!getData) {
                        return false
                    }
                    this.form.dollar = getData.value
                })
        },
        simpanData(){
            this.form.post('/api/master/simpan-dollar').then((response)=>{
                Toast.fire({
                    icon: 'success',
                    title: response.message
                });
                this.loadPostsData();
            }).catch(()=>{
                Toast.fire({
                    icon: 'error',
                    title: 'Some error occured! Please try again'
                });
            })
        }
    },
}
</script>
