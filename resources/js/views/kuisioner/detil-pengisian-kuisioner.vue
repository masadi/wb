<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Progres Pengisian Instrumen: Komponen {{title}}</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><router-link tag="a" to="/beranda">Beranda</router-link></li>
                            <li class="breadcrumb-item active">Progres Pengisian Instrumen</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <table class="table">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th scope="col" class="text-center">Aspek</th>
                                            <th scope="col" class="text-center">Bobot</th>
                                            <th scope="col" class="text-center">Jumlah Soal</th>
                                            <th scope="col" class="text-center">Soal Terjawab</th>
                                            <th scope="col" class="text-center">Persentase</th>
                                            <th scope="col" class="text-center">Nilai</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(value, name) in items">
                                            <td><a href="javascript:{}" v-on:click="isi_instrumen(aspek[name])">{{name}}</a></td>
                                            <td class="text-center">{{bobot[name]}}</td>
                                            <td class="text-center">{{jumlah_soal[name]}}</td>
                                            <td class="text-center">{{jumlah_terjawab[name]}}</td>
                                            <td class="text-center">{{persen[name]}}</td>
                                            <td class="text-center">{{nilai[name]}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
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
                title : '',
                jumlah_soal : {},
                bobot: {},
                jumlah_terjawab : {},
                persen : {},
                nilai : {},
                items: [],
                aspek:{},
            }
        },
        created() {
            this.loadPostsData()
        },
        methods: {
            isi_instrumen(aspek_id){
                //this.$router.replace({ name: 'proses_pengisian', params: { id: this.$route.params.id, new_page:aspek_id } })
                //this.$router.go({ name: 'proses_pengisian', params: { id: this.$route.params.id, new_page:aspek_id } })
                this.$router.push({ name: 'proses_pengisian', params: { id: this.$route.params.id, new_page:aspek_id } })
            },
            loadPostsData() {
                /*axios.post(`/api/kuisioner/progres`, {
                    //KIRIMKAN PARAMETER BERUPA PAGE YANG SEDANG DILOAD, PENCARIAN, LOAD PERPAGE DAN SORTING.
                    komponen_id: this.$route.params.id,
                    user_id: user.user_id,
                })*/
                axios.get(`/api/kuisioner/progres`, {
                    //KIRIMKAN PARAMETER BERUPA PAGE YANG SEDANG DILOAD, PENCARIAN, LOAD PERPAGE DAN SORTING.
                    params : {
                        user_id: user.user_id,
                        komponen_id: this.$route.params.id,
                    }
                })
                .then((response) => {
                    let getData = response.data
                    let getNilai = response.data.nilai
                    var tempNilai = {}
                    var tempBobot = {}
                    $.each(getNilai, function(key, value) {
                        tempNilai[value.aspek.nama] = value.total_nilai
                        //tempBobot[value.aspek.nama] = value.aspek.bobot
                    });
                    $.each(getData.output_bobot, function(key, value) {
                        tempBobot[key] = value
                    });
                    this.bobot = tempBobot
                    var tempJumlahSoal = {}
                    var tempJumlahTerjawab = {}
                    var tempPersen = {}
                    $.each(getData.data, function(key, value) {
                        var jumlah_soal = 0
                        var jumlah_terjawab = 0
                        var nilai = 0
                        $.each(value, function(index, val) {
                            jumlah_soal++
                            jumlah_terjawab += val.jawaban_count
                        })
                        let persen = (jumlah_terjawab / jumlah_soal)*(100);
                        persen = persen.toFixed(2);
                        tempJumlahSoal[key] = jumlah_soal
                        tempJumlahTerjawab[key] = jumlah_terjawab
                        tempPersen[key] = persen
                    })
                    this.jumlah_soal = tempJumlahSoal
                    this.jumlah_terjawab = tempJumlahTerjawab
                    this.persen = tempPersen
                    this.nilai = tempNilai
                    this.title = getData.title
                    this.items = getData.data
                    this.aspek = getData.output_aspek
                })
            }
        }
    }
</script>
