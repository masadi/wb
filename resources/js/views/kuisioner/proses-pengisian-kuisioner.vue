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
                                <h3>
                                    <i class="fas fa-check mr-1"></i>
                                    Kuesioner Komponen {{title}}
                                </h3>
                                <!--div class="card-tools">
                                        <button type="submit" class="btn btn-danger" :disabled="pakta_integritas">Simpan</button>
                                    </div-->
                            </div>
                            <div class="card-body">
                                <div class="sticky-top">
                                    <div class="info-box bg-info ">
                                        <div class="info-box-content text-center">
                                            <paginator :url="`${url}`" v-on:update-pagination-data="updatePaginationData"></paginator>
                                        </div>
                                    </div>
                                </div>
                                <div v-for="(value, name) in items">
                                    <h2>Aspek {{name}} </h2>
                                    <ol class="pl-4">
                                        <li class="mb-1" v-for="(item, index) in value">
                                            <input type="hidden" v-model="form.indikator_id[item.instrumen_id]">
                                            <input type="hidden" v-model="form.atribut_id[item.instrumen_id]">
                                            <input type="hidden" v-model="form.aspek_id[item.instrumen_id]">
                                            <input type="hidden" v-model="form.komponen_id[item.instrumen_id]">
                                            <span style="font-weight: 600;">{{item.pertanyaan}} <a href="javascript:{}" class="text-danger" v-on:click="petunjuk(item.instrumen_id)" title="Petunjuk Pengisian"><i class="fas fa-info-circle"></i></a></span>
                                            <div class="form-group">
                                                <div class="custom-control custom-radio mt-1" v-for="subs in item.subs">
                                                    <input class="custom-control-input" type="radio" v-bind:id="item.instrumen_id+subs.urut" v-model="form.instrumen_id[item.instrumen_id]" v-bind:value="subs.urut">
                                                    <label v-bind:for="item.instrumen_id+subs.urut" class="custom-control-label" style="font-weight: normal;">{{subs.urut}}. {{subs.pertanyaan}}</label>
                                                </div>
                                            </div>
                                            <div class="form-group" v-for="breakdown in item.breakdown">
                                                <table class="table table-bordered">
                                                    <tr>
                                                        <th>{{breakdown.breakdown}}</th>
                                                        <template v-for="question in breakdown.question.slice(0,1)">
                                                            <th v-for="answer in question.answer" width="150">{{answer.answer}}</th>
                                                        </template>
                                                    </tr>
                                                    <tr v-for="question in breakdown.question">
                                                        <td>{{question.question}}</td>
                                                        <td v-for="answer in question.answer">
                                                            <template v-if="answer.answer === 'Jumlah Total' && question.answer.length > 1">
                                                                <input v-if="answer.type === 'number'" class="form-control input-sm jumlah" type="number" step="1" min="0" v-model="form.answer_id[answer.answer_id]" readonly>
                                                            </template>
                                                            <template v-else>
                                                                <input v-if="answer.type === 'number'" class="form-control input-sm hitung" type="number" step="1" min="0" v-model="form.answer_id[answer.answer_id]" @input="filterInput">
                                                            </template>
                                                            <div v-if="answer.type === 'radio'" class="custom-control custom-radio mt-1">
                                                            <input class="custom-control-input" type="radio" v-bind:id="answer.answer_id" v-model="form.answer_id[answer.question_id]" v-bind:value="answer.urut">
                                                            <label v-bind:for="answer.answer_id" class="custom-control-label" style="font-weight: normal;">{{answer.answer}}</label>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </li>
                                    </ol>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="d-flex justify-content-between">
                                    <button v-bind:class="{ disabled: !previous }" type="button" class="btn btn-primary" v-on:click="newPage(previous)">&laquo; Komponen Sebelumnya</button>
                                    <!--button type="submit" class="btn btn-danger" :disabled="pakta_integritas">Simpan</button-->
                                    <b-button type="submit" squared variant="danger" :disabled="pakta_integritas">
                                        <b-spinner small v-show="show_spinner_simpan"></b-spinner>
                                        <span class="sr-only" v-show="show_spinner_simpan">Loading...</span>
                                        <span v-show="show_text_simpan">Simpan</span>
                                    </b-button>
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
                    <h5 class="modal-title">Keterangan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" v-html="parse"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
//import axios from 'axios' //IMPORT AXIOS
export default {
    data() {
        return {
            url: (this.$route.params.new_page) ? `/api/get-kuisioner?user_id=${user.user_id}&komponen_id=${this.$route.params.id}&page=${this.$route.params.new_page}` : `/api/get-kuisioner?user_id=${user.user_id}&komponen_id=${this.$route.params.id}`,
            komponen_id: this.$route.params.id,
            user_id: user.user_id,
            nilai: null,
            title: '-',
            form: new Form({
                user_id: '',
                verifikator_id: '',
                indikator_id: {},
                atribut_id: {},
                aspek_id: {},
                komponen_id: {},
                instrumen_id: {},
                answer_id: {},
            }),
            articles: [],
            items: [],
            current_page: 0,
            total: 0,
            per_page: 0,
            previous: null,
            next: null,
            text: `myLine1\nmyLine2`,
            content: {},
            pakta_integritas: false,
            parse: '',
            show_spinner_simpan: false,
            show_text_simpan: true,
        }
    },
    methods: {
        filterInput(e){
            console.log(e.target)
            var a = $(e.target).prevAll().find('.jumlah')
            console.log(a);
            e.target.value = e.target.value.replace(/[^0-9]+/g, '');
        },
        newPage(new_page, intern = false) {
            if (new_page) {
                this.url = `/api/get-kuisioner?user_id=${this.user_id}&komponen_id=${new_page}`
                if (!intern) {
                    this.$router.replace({
                        name: 'proses_pengisian',
                        params: {
                            id: new_page
                        }
                    })
                    this.$router.go({
                        name: 'proses_pengisian',
                        params: {
                            id: new_page
                        }
                    })
                }
            }
        },
        petunjuk(instrumen_id) {
            axios.post(`/api/kuisioner/parse-json`, {
                obj: this.contentPetunjuk[instrumen_id]
            }).then((response) => {
                this.parse = response.data
                $('#modalPetunjuk').modal('show');
            })
        },
        updatePaginationData(event) {
            let getData = event.data
            this.form.verifikator_id = (getData.user.sekolah.sekolah_sasaran) ? getData.user.sekolah.sekolah_sasaran.verifikator_id : null
            if (!getData.user.sekolah.sekolah_sasaran) {
                this.pakta_integritas = true
            } else if (getData.user.sekolah.sekolah_sasaran.pakta_integritas) {
                this.pakta_integritas = true
            }
            //console.log(this.pakta_integritas)
            this.pakta_integritas = false
            this.current_page = getData.aspek.current_page
            this.total = getData.aspek.total
            this.per_page = getData.aspek.per_page
            this.items = getData.data
            this.title = getData.title
            var tempData = {};
            var tempIndikator = {};
            var tempAtribut = {};
            var tempAspek = {};
            var tempKomponen = {};
            var tempPetunjuk = {};
            var tempDetilPetunjuk = {};
            var tempAnswer = {};
            $.each(getData.data, function (key, value) {
                $.each(value, function (index, val) {
                    $.each(val.breakdown, function (i, v) {
                        //console.log(v.question);
                        $.each(v.question, function (iq, vq) {
                            //console.log(vq.answer);
                            $.each(vq.answer, function (answer_id, answer) {
                                console.log(answer);
                                if(answer.nilai_answer){
                                    tempAnswer[answer.answer_id] = answer.nilai_answer.answer
                                }
                            })
                        })
                    })
                    tempIndikator[val.instrumen_id] = val.indikator_id;
                    tempAtribut[val.instrumen_id] = val.indikator.atribut_id;
                    tempAspek[val.instrumen_id] = val.indikator.atribut.aspek_id;
                    tempKomponen[val.instrumen_id] = val.indikator.atribut.aspek.komponen_id;
                    if (val.jawaban) {
                        tempData[val.instrumen_id] = val.jawaban.nilai;
                    }
                    tempPetunjuk[val.instrumen_id] = val.petunjuk_pengisian;
                    tempDetilPetunjuk[val.instrumen_id] = {
                        'petunjuk_pengisian': val.petunjuk_pengisian,
                        'indikator': val.indikator.nama,
                        'atribut': val.indikator.atribut.nama
                    }
                });
            });
            this.form.indikator_id = tempIndikator;
            this.form.atribut_id = tempAtribut;
            this.form.aspek_id = tempAspek;
            this.form.komponen_id = tempKomponen;
            this.form.instrumen_id = tempData;
            this.form.answer_id = tempAnswer;
            this.previous = getData.previous
            this.next = getData.next
            this.content = tempPetunjuk
            this.contentPetunjuk = tempDetilPetunjuk
            window.scrollTo(0, 100);
            this.show_spinner_simpan = false
            this.show_text_simpan = true
        },
        insertData() {
            this.show_spinner_simpan = true
            this.show_text_simpan = false
            let page_url = (this.current_page < this.total) ? (this.current_page + 1) : null
            let next_page = this.next;
            let new_id = (this.komponen_id + 1)
            this.form.user_id = user.user_id
            this.form.post('/api/simpan-jawaban').then((response) => {
                if (page_url) {
                    $.each(this.$children, function (key, value) {
                        if (value.simple) {
                            value.getData(`&page=${page_url}`)
                        }
                    })
                } else if (next_page) {
                    this.newPage(next_page);
                }
                Toast.fire({
                    icon: 'success',
                    title: 'Jawaban berhasil disimpan',
                }).then(() => {
                    if (!page_url && !next_page) {
                        this.$router.push({
                            path: '/kuisioner/pengisian'
                        })
                    }
                });
            })
        },
    }
}
</script>
