<template>
    <div>
        isi batang {{isi_batang}}
    </div>
</template>

<script>
export default {
    props: {
        isi_batang: {
            type: Number,
            default: 0
        },
    },
}
</script>