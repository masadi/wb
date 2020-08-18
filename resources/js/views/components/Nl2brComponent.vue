<template>
  <nl2br tag="p" :text="text" />
</template>

<script>
import Nl2br from 'vue-nl2br'

export default {
    name: 'Nl2brComponent',
    components: {
        Nl2br,
    },
    props: {
        tag:{
            type: String,
        },
        text: {
            type: String,
            default: `myLine1\nmyLine2`
        }
    },
}
</script>