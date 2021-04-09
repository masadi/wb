<template>
    <b-modal id="modal-xl" size="xl" ref="addSekolah" title="Tambah Sekolah Sasaran">
        <sekolah-sasaran :verifikator_id="verifikator_id" :permintaan="permintaan"></sekolah-sasaran>
        <template v-slot:modal-footer>
            <div class="float-right">
                <b-button variant="secondary" size="sm" v-on:click="hideModal">Tutup</b-button>
            </div>
        </template>
    </b-modal>
</template>
<script>
    import SekolahSasaran from "./../sekolah_sasaran";
    export default {
        components: {
            'sekolah-sasaran': SekolahSasaran //REGISTER COMPONENT DATATABLE
        },
        data() {
            return {
                verifikator_id: '-',
                permintaan: 'add',
            }
        },
        methods: {
            hideModal(){
                this.$refs.addSekolah.hide();
            },
            show: function (val) {
                this.verifikator_id = val
                this.$refs.addSekolah.show();
            },  
        }
    }
</script>