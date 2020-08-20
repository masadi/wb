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
                        <form class="form-horizontal">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Silahkan pilih Komponen/Aspek/Instrumen yang akan dirubah</h3>
                                </div>
                                <div class="card-body">
                                    <h4></h4>
                                    <div class="form-group row">
                                        <label for="komponen_id" class="col-sm-2 col-form-label">Komponen</label>
                                        <div class="col-sm-10">
                                            <v-select :options="komponen" label="text" index="value" @input="getAspek" v-model="komponen_id" placeholder="== Pilih Komponen == "></v-select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="aspek_id" class="col-sm-2 col-form-label">Aspek</label>
                                        <div class="col-sm-10">
                                            <v-select :options="aspek" label="text" index="value" @input="getInstrumen" v-model="aspek_id" placeholder="== Pilih Aspek == "></v-select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="instrumen_id" class="col-sm-2 col-form-label">Instrumen</label>
                                        <div class="col-sm-10">
                                            <v-select :options="instrumen" label="text" index="value" @input="getSub" v-model="instrumen_id" placeholder="== Pilih Instrumen == "></v-select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="custom-control custom-radio mt-1" v-for="sub in subs">
                                            <input class="custom-control-input" type="radio" v-bind:id="sub.ins_id+sub.urut" v-bind:name="`instrumen_id.${sub.ins_id}`" v-model="instrumen_id[sub.ins_id]" v-bind:value="sub.urut">
                                            <label v-bind:for="sub.ins_id+sub.urut" class="custom-control-label" style="font-weight: normal;">{{sub.urut}}. {{sub.pertanyaan}}</label>
                                        </div>
                                    </div>
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
    //KETIKA COMPONENT INI DILOAD
    created() {
        //MAKA AKAN MENJALANKAN FUNGSI BERIKUT
        this.loadPostsData()
    },
    data() {
        return {
            komponen_id: 0,
            aspek_id: 0,
            instrumen_id: 0,
            komponen: [],
            aspek: [],
            instrumen: [],
            subs: {},
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
        getAspek(e){
            axios.post(`/api/verifikasi/aspek`, {
                komponen_id: e.value,
            })
            .then((response) => {
                this.aspek = response.data.result
                this.aspek_id = 0
            })
        },
        getInstrumen(e){
            axios.post(`/api/verifikasi/instrumen`, {
                aspek_id: e.value,
            })
            .then((response) => {
                this.instrumen = response.data.result
                this.instrumen_id = 0
            })
        },
        getSub(e){
            axios.post(`/api/verifikasi/subs`, {
                instrumen_id: e.value,
            })
            .then((response) => {
                let getData = response.data
                console.log(getData);
                this.subs = getData.subs
                if(getData.nilai_instrumen){
                    this.instrumen_id[getData.instrumen_id] = getData.nilai_instrumen.nilai
                }
            })
        },
        loadPostsData() {
            axios.post(`/api/verifikasi/komponen`, {
                test: 'test',
            })
            .then((response) => {
                //JIKA RESPONSENYA DITERIMA
                console.log(response.data.result);
                this.komponen = response.data.result //MAKA ASSIGN DATA POSTINGAN KE DALAM VARIABLE ITEMS
                this.komponen_id = 0
            })
        }
    }
}
</script>
