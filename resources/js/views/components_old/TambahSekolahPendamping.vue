<template>
<b-modal id="modal-xl" size="xl" ref="addSekolah" title="Tambah Sekolah Binaan">
    <sekolah-sasaran-pendamping :pendamping_id="pendamping_id" :permintaan="permintaan"></sekolah-sasaran-pendamping>
    <template v-slot:modal-footer>
        <div class="float-right">
            <b-button variant="secondary" size="sm" v-on:click="hideModal">Tutup</b-button>
        </div>
    </template>
</b-modal>
</template>

<script>
import SekolahSasaranPendamping from "./../sekolah_sasaran_pendamping";
export default {
    components: {
        'sekolah-sasaran-pendamping': SekolahSasaranPendamping //REGISTER COMPONENT DATATABLE
    },
    data() {
        return {
            pendamping_id: '-',
            permintaan: 'add',
        }
    },
    methods: {
        hideModal() {
            this.$refs.addSekolah.hide();
        },
        show: function (val) {
            this.pendamping_id = val
            this.$refs.addSekolah.show();
        },
    }
}
</script>
