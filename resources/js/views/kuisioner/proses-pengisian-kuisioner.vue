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
                                        <h2>Aspek {{name}} </h2>
                                        <ol class="pl-4">
                                            <li class="h5 mb-1" v-for="(item, index) in value">
                                                <input type="hidden" v-model="form.indikator_id[item.instrumen_id]">
                                                <input type="hidden" v-model="form.atribut_id[item.instrumen_id]">
                                                <input type="hidden" v-model="form.aspek_id[item.instrumen_id]">
                                                <input type="hidden" v-model="form.komponen_id[item.instrumen_id]">
                                                <span style="font-weight: 600;">{{item.pertanyaan}} <button type="button" class="btn btn-primary" v-on:click="petunjuk(item.instrumen_id)" title="Petunjuk Pengisian"><i class="fas fa-search-plus"></i></button></span>
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
                                    <div class="d-flex justify-content-between">
                                    <button v-bind:class="{ disabled: !previous }" type="button" class="btn btn-primary" v-on:click="newPage(previous)">&laquo; Komponen Sebelumnya</button>
                                    <button type="submit" class="btn btn-danger">Simpan</button>
                                    <button v-bind:class="{ disabled: !next }" type="button" class="btn btn-primary" v-on:click="newPage(next)">Komponen Berikutnya &raquo;</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
        <div class="modal fade" id="modalPetunjuk" tabindex="-1" role="dialog" aria-labelledby="modalPetunjuk" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Petunjuk Pengisian</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <nl2br :text="text" />
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import Nl2brComponent from './../components/Nl2brComponent';
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
                previous: null,
                next:null,
                text: `myLine1\nmyLine2`,
                content:{}
            }
        },
        //created() {
            //MAKA AKAN MENJALANKAN FUNGSI BERIKUT
            //this.updatePaginationData('')
        //},
        components: {
            'nl2br': Nl2brComponent
        },
        methods: {
            newPage(new_page, intern = false){
                if(new_page){
                    this.url = `/api/get-kuisioner?user_id=${this.user_id}&komponen_id=${new_page}`
                    if(!intern){
                        this.$router.replace({ name: 'proses_pengisian', params: { id: new_page } })
                        this.$router.go({ name: 'proses_pengisian', params: { id: new_page } })
                    }
                    //this.$router.push({ name: 'proses_pengisian', params: { id: new_page } })
                    
                }
            },
            petunjuk(instrumen_id){
                this.text = this.content[instrumen_id]
                console.log(this.text);
                $('#modalPetunjuk').modal('show');
            },
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
                var tempPetunjuk = {};
                $.each(getData.data, function(key, value) {
                    $.each(value, function(index, val) {
                        tempIndikator[val.instrumen_id] = val.indikator_id; 
                        tempAtribut[val.instrumen_id] = val.indikator.atribut_id; 
                        tempAspek[val.instrumen_id] = val.indikator.atribut.aspek_id; 
                        tempKomponen[val.instrumen_id] = val.indikator.atribut.aspek.komponen_id; 
                        if(val.jawaban){
                            tempData[val.jawaban.instrumen_id] = val.jawaban.nilai; 
                        }
                        tempPetunjuk[val.instrumen_id] = val.petunjuk_pengisian; 
                    });
                });
                this.form.indikator_id = tempIndikator;
                this.form.atribut_id = tempAtribut;
                this.form.aspek_id = tempAspek;
                this.form.komponen_id = tempKomponen;
                this.form.instrumen_id = tempData;
                this.previous = getData.previous
                this.next = getData.next
                this.content = tempPetunjuk
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
                    console.log(response)
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
                    this.previous = getData.previous
                    this.next = getData.next
                    window.scrollTo(0,100);
                })
            },
            insertData(){
                let page_url = (this.current_page < this.total) ? (this.current_page + 1) : null
                let next_page = this.next;
                let new_id = (this.komponen_id + 1)
                this.form.user_id = user.user_id
                this.form.post('/api/simpan-jawaban').then((response)=>{
                    //
                    if(page_url){
                        //this.newPage(page_url);
                        $.each(this.$children, function(key, value) {
                            value.getData(`&page=${page_url}`)
                            //value.getData(`&new_page=${new_page}`)
                        })
                    } else if(next_page){
                        this.newPage(next_page);
                    }
                    //this.url
                    Toast.fire({
                        icon: 'success',
                        title: 'Jawaban berhasil disimpan',
                    }).then(() => {
                        if(!page_url && !next_page){
                            this.$router.push({path: '/kuisioner/pengisian'})
                        }
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
