<template>
    <div class="loader" v-if="loading"></div>
</template>
<script>
export default {
    props: {
        loading:{
            type: Boolean,
            default: false,
        }
    },
}
</script>