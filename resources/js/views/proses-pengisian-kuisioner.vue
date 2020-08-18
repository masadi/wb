<template>
    <div>
        <div class="content-header">
        </div>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <form @submit.prevent="insertData()" method="post">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-check mr-1"></i>
                                        Kuisioner Komponen {{title}}
                                    </h3>
                                    <div class="card-tools">
                                        <button type="submit" class="btn btn-danger">Simpan</button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="sticky-top">
                                    <div class="info-box bg-info ">
                                        <div class="info-box-content text-center">
                                            <paginator :url ="`${url}`" v-on:update-pagination-data="updatePaginationData"></paginator>
                                        </div>
                                    </div>
                                    </div>
                                    <div v-for="(value, name) in items">
                                        <h2>Aspek {{name}}</h2>
                                        <ol class="pl-4">
                                            <li class="h5 mb-1" v-for="(item, index) in value">
                                                <input type="hidden" v-model="form.indikator_id[item.instrumen_id]">
                                                <input type="hidden" v-model="form.atribut_id[item.instrumen_id]">
                                                <input type="hidden" v-model="form.aspek_id[item.instrumen_id]">
                                                <input type="hidden" v-model="form.komponen_id[item.instrumen_id]">
                                                <span style="font-weight: 600;">{{item.pertanyaan}}</span>
                                                <div class="form-group">
                                                    <div class="custom-control custom-radio mt-1" v-for="subs in item.subs">
                                                        <input class="custom-control-input" type="radio" v-bind:id="item.instrumen_id+subs.urut" v-bind:name="`instrumen_id.${item.instrumen_id}`" v-model="form.instrumen_id[item.instrumen_id]" v-bind:value="subs.urut">
                                                        <label v-bind:for="item.instrumen_id+subs.urut" class="custom-control-label" style="font-weight: normal;">{{subs.urut}}. {{subs.pertanyaan}}</label>
                                                    </div>
                                                </div>
                                            </li>
                                        </ol>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-danger float-right row" v-on:update-pagination-data="updatePaginationData">Simpan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>
<script>
    import axios from 'axios' //IMPORT AXIOS
    export default {
    //KETIKA COMPONENT INI DILOAD
        data() {
            return {
                url: `/api/get-kuisioner?user_id=${user.user_id}&komponen_id=${this.$route.params.id}`,
                komponen_id: this.$route.params.id,
                user_id: user.user_id,
                nilai: null,
                title:'-',
                form: new Form({
                    user_id : '',
                    indikator_id: {},
                    atribut_id: {},
                    aspek_id: {},
                    komponen_id: {},
                    instrumen_id: {},
                }),
                articles: [],
                items: [],
                current_page: 0,
                total: 0,
                per_page: 0,
            }
        },
        //created() {
            //MAKA AKAN MENJALANKAN FUNGSI BERIKUT
            //this.updatePaginationData('')
        //},
        methods: {
            updatePaginationData(event) {
                let getData = event.data
                this.current_page = getData.aspek.current_page
                this.total = getData.aspek.total
                this.per_page = getData.aspek.per_page
                this.items = getData.data //MAKA ASSIGN DATA POSTINGAN KE DALAM VARIABLE ITEMS
                this.title = getData.title
                var tempData = {};
                var tempIndikator = {};
                var tempAtribut = {};
                var tempAspek = {};
                var tempKomponen = {};
                $.each(getData.data, function(key, value) {
                    $.each(value, function(index, val) {
                        tempIndikator[val.instrumen_id] = val.indikator_id; 
                        tempAtribut[val.instrumen_id] = val.indikator.atribut_id; 
                        tempAspek[val.instrumen_id] = val.indikator.atribut.aspek_id; 
                        tempKomponen[val.instrumen_id] = val.indikator.atribut.aspek.komponen_id; 
                        if(val.jawaban){
                            tempData[val.jawaban.instrumen_id] = val.jawaban.nilai; 
                        }
                    });
                });
                this.form.indikator_id = tempIndikator;
                this.form.atribut_id = tempAtribut;
                this.form.aspek_id = tempAspek;
                this.form.komponen_id = tempKomponen;
                this.form.instrumen_id = tempData;
                window.scrollTo(0,100);
                //this.insertData();
            },
            loadPostsData() {

                //LAKUKAN REQUEST KE API UNTUK MENGAMBIL DATA POSTINGAN
                axios.get(`/api/get-kuisioner`, {
                    //KIRIMKAN PARAMETER BERUPA PAGE YANG SEDANG DILOAD, PENCARIAN, LOAD PERPAGE DAN SORTING.
                    params : {
                        user_id: user.user_id,
                        komponen_id: this.$route.params.id,
                        page: this.current_page,
                        //aktif: this.current_page + 1,
                    }
                })
                /*axios.post(`/api/get-kuisioner`, {
                    //KIRIMKAN PARAMETER BERUPA PAGE YANG SEDANG DILOAD, PENCARIAN, LOAD PERPAGE DAN SORTING.
                    komponen_id: this.$route.params.id,
                    user_id: user.user_id,
                })*/
                .then((response) => {
                    //this.$emit('update-url', response);
                    //JIKA RESPONSENYA DITERIMA
                    let getData = response.data
                    this.current_page = getData.aspek.current_page
                    this.items = getData.data //MAKA ASSIGN DATA POSTINGAN KE DALAM VARIABLE ITEMS
                    this.title = getData.title
                    var tempData = {};
                    var tempIndikator = {};
                    var tempAtribut = {};
                    var tempAspek = {};
                    var tempKomponen = {};
                    $.each(getData.data, function(key, value) {
                        $.each(value, function(index, val) {
                            tempIndikator[val.instrumen_id] = val.indikator_id; 
                            tempAtribut[val.instrumen_id] = val.indikator.atribut_id; 
                            tempAspek[val.instrumen_id] = val.indikator.atribut.aspek_id; 
                            tempKomponen[val.instrumen_id] = val.indikator.atribut.aspek.komponen_id; 
                            if(val.jawaban){
                                tempData[val.jawaban.instrumen_id] = val.jawaban.nilai; 
                            }
                        });
                    });
                    this.form.indikator_id = tempIndikator;
                    this.form.atribut_id = tempAtribut;
                    this.form.aspek_id = tempAspek;
                    this.form.komponen_id = tempKomponen;
                    this.form.instrumen_id = tempData;
                    window.scrollTo(0,100);
                })
            },
            insertData(){
                this.form.user_id = user.user_id
                this.form.post('/api/simpan-jawaban').then((response)=>{
                    this.loadPostsData();
                    Toast.fire({
                        icon: 'success',
                        title: 'Jawaban berhasil disimpan',
                    });
                })/*.catch((e)=>{
                    Toast.fire({
                        icon: 'error',
                        title: 'Some error occured! Please try again'
                    });
                })*/
            },
        }
    }
</script>
