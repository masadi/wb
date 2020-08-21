<template>
  <loader object="#ff9633" color1="#ffffff" color2="#17fd3d" size="5" speed="2" bg="#343a40" objectbg="#999793" opacity="80" name="circular"></loader>
</template>
<script>
export default {
    //KETIKA COMPONENT INI DILOAD
    mounted() {
        var vm = this;
        $(vm.$el).hide()
    },
}
</script>