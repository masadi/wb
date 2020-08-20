<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Proses Verifikasi dan Validasi</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><router-link tag="a" to="/beranda">Beranda</router-link></li>
                            <li class="breadcrumb-item active">Proses Verifikasi dan Validasi</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <form class="form-horizontal" @submit.prevent="insertData()" method="post">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Silahkan pilih Komponen/Aspek/Instrumen yang akan dirubah</h3>
                                </div>
                                <div class="card-body">
                                    <input type="hidden" name="verifikator_id" :value="form.verifikator_id">
                                    <input type="hidden" name="user_id" :value="form.user_id">
                                    <div class="form-group row">
                                        <label for="komponen_id" class="col-sm-2 col-form-label">Komponen</label>
                                        <div class="col-sm-10">
                                            <v-select :options="form.komponen" label="text" index="value" @input="getAspek" v-model="form.komponen_id" placeholder="== Pilih Komponen == "></v-select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="aspek_id" class="col-sm-2 col-form-label">Aspek</label>
                                        <div class="col-sm-10">
                                            <v-select :options="form.aspek" label="text" index="value" @input="getInstrumen" v-model="form.aspek_id" placeholder="== Pilih Aspek == "></v-select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="instrumen_id" class="col-sm-2 col-form-label">Instrumen</label>
                                        <div class="col-sm-10">
                                            <v-select :options="form.instrumen" label="text" index="value" @input="getSub" v-model="form.instrumen_id" placeholder="== Pilih Instrumen == "></v-select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="custom-control custom-radio mt-1" v-for="sub in form.jawaban">
                                            <input class="custom-control-input" type="radio" v-bind:id="sub.ins_id+sub.urut" v-bind:name="`instrumen_id.${sub.ins_id}`" v-model="form.nilai" v-bind:value="sub.urut">
                                            <label v-bind:for="sub.ins_id+sub.urut" class="custom-control-label" style="font-weight: normal;">{{sub.urut}}. {{sub.pertanyaan}}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer" v-show="simpan">
                                    <button type="submit" class="btn btn-primary">Simpan</button>
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
//import Select2 from './../components/Select2'
export default {
    created() {
        //MAKA AKAN MENJALANKAN FUNGSI BERIKUT
        this.loadPostsData()
    },
    data() {
        return {
            form: new Form({
                user_id: this.$route.params.sekolah_id,
                verifikator_id:user.user_id,
                komponen_id: 0,
                aspek_id: 0,
                instrumen_id: 0,
                nilai: 0,
                komponen: [],
                aspek: [],
                instrumen: [],
                jawaban: [],
            }),
            simpan:false,
        }
    },
    /*data: {
        selected: "selected",
        rowId: 10
    },*/
    components: {
        //"select2": Select2
        //"vue-select": require("vue-select2")
    },
    methods: {
        insertData(){
            this.form.post('/api/verifikasi/simpan').then((response)=>{
                this.form.verifikator_id = 0;
                this.form.user_id = 0
                this.form.komponen_id = 0
                this.form.aspek_id = 0
                this.form.instrumen_id = 0
                this.form.nilai = 0
                this.form.komponen = []
                this.form.aspek = []
                this.form.instrumen = []
                this.form.jawaban = []
                Toast.fire({
                    icon: 'success',
                    title: 'Jawaban berhasil disimpan',
                }).then(() => {
                    this.loadPostsData()
                })
            })
        },
        getAspek(e){
            axios.post(`/api/verifikasi/aspek`, {
                komponen_id: e.value,
            })
            .then((response) => {
                this.form.aspek = response.data.result
                this.form.aspek_id = 0
                this.form.instrumen = []
                this.form.jawaban = []
            })
        },
        getInstrumen(e){
            axios.post(`/api/verifikasi/instrumen`, {
                aspek_id: e.value,
            })
            .then((response) => {
                this.form.instrumen = response.data.result
                this.form.instrumen_id = 0
                this.form.jawaban = []
            })
        },
        getSub(e){
            axios.post(`/api/verifikasi/subs`, {
                instrumen_id: e.value,
                user_id: this.$route.params.sekolah_id,
            })
            .then((response) => {
                let getData = response.data
                this.form.jawaban = getData.subs
                if(getData.jawaban){
                    this.form.nilai = getData.jawaban.nilai
                }
                this.simpan = true
            })
        },
        loadPostsData() {
            axios.post(`/api/verifikasi/komponen`, {
                user_id: this.$route.params.sekolah_id,
            })
            .then((response) => {
                //JIKA RESPONSENYA DITERIMA
                this.form.verifikator_id = user.user_id
                this.form.user_id = response.data.user_id
                this.form.komponen_id = 0
                this.form.aspek_id = 0
                this.form.instrumen_id = 0
                this.form.nilai = 0
                this.form.komponen = response.data.result //MAKA ASSIGN DATA POSTINGAN KE DALAM VARIABLE ITEMS
                this.form.aspek = []
                this.form.instrumen = []
                this.form.jawaban = []
            })
        }
    }
}
</script>
