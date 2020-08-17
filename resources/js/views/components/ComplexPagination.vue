<template>
    <span>
        <!-- Left Arrow -->
        <li class="page-item" v-bind:class="{ 'disabled': !pagination.prev_page }">
            <a v-on:click.prevent="linkClick(pagination.current_page - 1)" class="page-link" href="#" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
                <span class="sr-only">Previous</span>
            </a>
        </li>

        <li class="page-item" v-bind:key="1" v-bind:class="{ 'active' : pagination.current_page === 1 }">
            <a v-on:click.prevent="linkClick(1)" class="page-link" href="#">1</a>
        </li>

        <li v-if="pagination.current_page > 4" class="page-item disabled" aria-disabled="true"><span class="page-link">...</span></li>

        <li v-if="calculateLowerBound(pagination.current_page, 2)"
            class="page-item">
            <a v-on:click.prevent="linkClick(pagination.current_page - 2)"
               class="page-link" href="#">
                {{ pagination.current_page - 2 }}
            </a>
        </li>

        <li v-if="calculateLowerBound(pagination.current_page, 1)"
            class="page-item">
            <a v-on:click.prevent="linkClick(pagination.current_page - 1)"
               class="page-link" href="#">
                {{ pagination.current_page - 1 }}
            </a>
        </li>

        <li v-if="pagination.current_page !== 1 && pagination.current_page !== pagination.last_page"
            v-bind:class="{ 'active' : pagination.current_page }"
            class="page-item">
            <a v-on:click.prevent="linkClick(pagination.current_page)" class="page-link" href="#">
                {{ pagination.current_page }}
            </a>
        </li>

        <li v-if="calculateUpperBound(pagination.current_page, 1)"
            class="page-item">
            <a v-on:click.prevent="linkClick(pagination.current_page + 1)" class="page-link" href="#">
                {{ pagination.current_page + 1 }}
            </a>
        </li>

        <li v-if="calculateUpperBound(pagination.current_page, 2)"
            class="page-item">
            <a v-on:click.prevent="linkClick(pagination.current_page + 2)" class="page-link" href="#">
                {{ pagination.current_page + 2 }}
            </a>
        </li>

        <li v-if="pagination.current_page < (pagination.last_page - 3)" class="page-item disabled" aria-disabled="true"><span class="page-link">...</span></li>

        <li class="page-item" v-bind:key="pagination.last_page"
            v-bind:class="{ 'active' : pagination.current_page === pagination.last_page }">
            <a v-on:click.prevent="linkClick(pagination.last_page)" class="page-link" href="#">
                {{ pagination.last_page }}
            </a>
        </li>

        <!-- Right Arrow -->
        <li class="page-item" v-bind:class="{'disabled': !pagination.next_page} ">
            <a v-on:click.prevent="linkClick(pagination.current_page + 1)" class="page-link" href="#" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
                <span class="sr-only">Next</span>
            </a>
        </li>
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
            linkClick(event){
                this.$emit('update-url', '&page=' + event);
            },
            calculateLowerBound(current, value){
                return current - value !== 0 && current - value !== 1 && current - value !== -1;
            },
            calculateUpperBound(current, value){
                return current + value < this.pagination.last_page;
            }
        }
    }
</script>
<style scoped>
    span {
        display: inherit;
    }
</style>