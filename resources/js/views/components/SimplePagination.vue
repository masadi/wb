<template>

    <span>
        <!-- Left Arrow -->
        <div class="d-flex justify-content-between">
            <a v-on:click.prevent="linkClick(pagination.current_page - 1)" class="btn btn-lg" v-bind:class="{ 'disabled': !pagination.prev_page }" href="#" aria-label="Previous">
                <span aria-hidden="true"><i class="fas fa-chevron-left" style="font-size: 48px;"></i></span>
                <span class="sr-only">Previous</span>
            </a>
            <span class="h4 mt-3" >Aspek {{(pagination.aktif) ? pagination.aktif : pagination.current_page}} dari {{pagination.total}} aspek</span>
            <a v-on:click.prevent="linkClick(pagination.current_page + 1)" class="btn btn-lg" v-bind:class="{'disabled': !pagination.next_page} " href="#" aria-label="Next">
                <span aria-hidden="true"><i class="fas fa-chevron-right" style="font-size: 48px;"></i></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </span>

</template>
<script>
    export default {
        props: {
            pagination: {
                type: Object
            },
        },
        methods: {
            pageAktif(){
                return 'asd'
            },
            linkClick(event){
                this.$emit('update-url', '&page=' + event);
            }
        }
    }
</script>