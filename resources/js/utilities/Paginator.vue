<template>
    <nav aria-label="Pagination" v-if="pagination">
        <!-- Simple Pagination less than 5 pages -->
        <SimplePagination v-if="simple" :pagination="pagination" v-on:update-url="getData"></SimplePagination>
        <!-- Complex Pagination more than 5 pages -->
        <ComplexPagination v-else :pagination="pagination" v-on:update-url="getData"></ComplexPagination>
    </nav>
</template>
<script>
    import SimplePagination from './../views/components/SimplePagination';
    import ComplexPagination from './../views/components/ComplexPagination';

    export default {
        components: {
            SimplePagination,
            ComplexPagination
        },
        props: {
            url: {
                type: String,
                default: '',
                simple: true,
            }
        },
        data(){
            return {
                data: {},
                pagination: '',
            }
        },
        methods: {
            getData(page_url){
                let url = page_url === undefined ? this.url : this.url + page_url;

                axios.get(url)
                    .then(response => {
                        this.data = response;
                        if(this.data.length !== 0){
                            this.makePagination(response.data.aspek);
                        }
                        this.$emit('update-pagination-data', this.data);
                    })
            },
            makePagination(meta){
                const pagination = {
                    current_page: meta.current_page,
                    last_page:    meta.last_page,
                    to:           meta.to,
                    from:         meta.from,
                    total:        meta.total,
                    next_page:    meta.next_page_url,
                    prev_page:    meta.prev_page_url,
                    aktif:        meta.aktif,
                };
                this.pagination = pagination;
                this.simple = true;//pagination.last_page < 6;
            }
        },
        created(){
            this.getData();
        }
    }
</script>